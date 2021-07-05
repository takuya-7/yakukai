<?php

// フルパスでfunction.phpを取得
require_once(dirname(__FILE__) . '/../function.php');

class FunctionTest extends PHPUnit\Framework\TestCase{

  public function testGetErrMsgTrue(){
    global $err_msg;
    $err_msg['number'] = MSG12;
    $result = getErrMsg('number');
    $this->assertEquals($err_msg['number'], $result);
  }

  public function testValidNumberTrue(){
    validNumber('111', 'number');
    $result = getErrMsg('number');
    $this->assertNull($result);
  }
  public function testValidNumberFalse(){
    validNumber('１２３', 'number');
    $result = getErrMsg('number');
    $this->assertEquals(MSG12, $result);
  }
  public function testValidNumberFalse2(){
    validNumber('一二三', 'number');
    $result = getErrMsg('number');
    $this->assertEquals(MSG12, $result);
  }
  public function testValidNumberFalse3(){
    validNumber('いち', 'number');
    $result = getErrMsg('number');
    $this->assertEquals(MSG12, $result);
  }

  public function testGetUserReturnsDbData(){
    $result = getUser(2);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetUserReturnsFalse(){
    $result = getUser(0);
    $this->assertFalse($result);
  }
}