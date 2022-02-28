# ヤクカイ

https://yakukai.net/

ヤクカイは、薬剤師に特化したクチコミ情報共有プラットフォームです。
調剤薬局やドラッグストアのクチコミを閲覧することができ、より良い転職に活かせます。
業界初の「働く人の幸福度」という視点を取り入れ、幸福度を上げる転職を応援します。

<img width="943" alt="yakukai" src="https://user-images.githubusercontent.com/79587217/133016171-2e028621-661c-41c4-8874-0f8aa6d42c9b.png">

# 使用技術一覧

- PHP 7.4.9
- MySQL 5.7.30
- Nginx
- Docker
- AWS
  - VPC, EC2, RDS, ALB, Route53, CloudFront, ACM, IAM
- CircleCI
- jQuery
- Chart.js
- SCSS
- Bootstrap
- PHPUnit
- Node.js
- gulp

# インフラ構成図

![infra_yakukai](https://user-images.githubusercontent.com/79587217/133016591-0d60a25c-700a-406f-a4d7-033f46dbae07.jpg)

- Githubへpush時、CircleCIにてdocker-composeで環境構築後、自動でPHPUnitによるテストが実行されます
- テスト成功時、本番用のDocker環境を構築後、自動デプロイされます

# 機能一覧

- ユーザー登録、ログイン・ログアウト、退会機能
- パスワード再発行機能
- ユーザー情報編集機能
  - プロフィール、メールアドレス、パスワード
- クチコミ投稿・編集・表示機能
  - 文字数カウント機能
  - 文字数不足による送信ボタン無効化機能
  - 評価値の平均算出、評価表示機能（ハート等）
  - 評価値のレーダーチャート表示機能（Chart.js）
- ページネーション機能
- 処理成功メッセージ表示機能
- いいね機能（Ajax）
  - いいね数表示、いいね数順でクチコミ表示
- クチコミ検索機能
  - 企業名・都道府県・業種で絞り込み可能
  - 評価値・クチコミ数で表示順変更可能
- クチコミランキング表示機能
  - 評価値・クチコミ数にて
- レスポンシブ対応
  - ハンバーガーメニュー等

# DB設計

![yakukai_ERD](https://user-images.githubusercontent.com/79587217/133018746-baf15b94-811f-4f5a-af54-4a8348e6ade9.jpg)

- データ保存、テーブル結合、データ検索・出力表示のしやすさを考えてDB構築した結果、第3正規形まで満たす形に

# 注意事項

現在デモサイトとして運用中です。
記載されているクチコミはサンプルで、実際のクチコミではありません。
