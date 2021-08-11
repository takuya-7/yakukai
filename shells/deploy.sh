#!/bin/sh
set -ex

# 環境変数設定
export AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}
export AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}
export AWS_DEFAULT_REGION="ap-northeast-1"

export USER_NAME=${USER_NAME}
export HOST_NAME=${HOST_NAME}

# EC2のcircleci用のセキュリティグループを指定
MY_SECURITY_GROUP="sg-0bd112560cd8f2e44"
# 自分（circleci）のIPアドレスを取得
MY_IP=`curl -f -s ifconfig.me`

# セキュリティグループにcircleciのIPアドレスを追加
aws ec2 authorize-security-group-ingress --group-id $MY_SECURITY_GROUP --protocol tcp --port 22 --cidr $MY_IP/32
# SSH接続してデプロイ
ssh $USER_NAME@$HOST_NAME "cd /var/www/html/yakukai/ && mkdir test_circleci && git pull"
# セキュリティグループのインバウンドルールを削除
trap "aws ec2 revoke-security-group-ingress --group-id $MY_SECURITY_GROUP --protocol tcp --port 22 --cidr $MY_IP/32" 0 1 2 3 15