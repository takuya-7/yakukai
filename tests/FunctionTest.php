<?php
// 単体テスト

// フルパスでfunction.phpを取得
require_once(dirname(__FILE__) . '/../function.php');

class FunctionTest extends PHPUnit\Framework\TestCase{

  // =================================
  // Database
  // =================================
  // dbConnect()
  public function testDbConnectTrue(){
    $dbh = dbConnect();
    $this->assertIsObject($dbh);
  }
  // queryPost()
  public function testQueryPostTrue(){
    $dbh = dbConnect();
    $sql = 'SELECT name FROM company WHERE id = :id';
    $data = array(':id' => 1);
    $stmt = queryPost($dbh, $sql, $data);
    $this->assertTrue($stmt->execute($data));
  }
  // getUser()
  public function testGetUserReturnsDbData(){
    $result = getUser(2);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetUserReturnsFalse(){
    $result = getUser(0);
    $this->assertFalse($result);
  }
  // getPost()
  public function testGetPostReturnsDbData(){
    $result = getPost(1);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetPostReturnsFalse(){
    $result = getPost(0);
    $this->assertEmpty($result);
  }
  // getPostAll()
  public function testGetPostAllReturnsDbData(){
    $result = getPostAll(1);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetPostAllReturnsFalse(){
    $result = getPostAll(0);
    $this->assertFalse($result['post']);
  }
  // getPostList()
  public function testGetPostListReturnsDbData(){
    $result = getPostList(1);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetPostListReturnsFalse(){
    $result = getPostList(0);
    $this->assertEmpty($result);
  }
  // getRatingByUserId
  public function testGetRatingByUserIdReturnsDbData(){
    $result = getRatingByUserId(1);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetRatingByUserIdReturnsFalse(){
    $result = getRatingByUserId(0);
    $this->assertEmpty($result);
  }
  // getRatings()
  public function testGetRatingsReturnsDbData(){
    $user_id = 1;
    $dbPostData = getPost($user_id);
    $result = getRatings($dbPostData[0]['id'], $user_id, $dbPostData[0]['company_id']);
    $this->assertTrue(count($result) > 0);
  }
  // getAnswer()
  public function testGetAnswerReturnsDbData(){
    $result = getAnswer(1);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetAnswerReturnsFalse(){
    $result = getAnswer(0);
    $this->assertEmpty($result);
  }
  // getAnswers()
  public function testGetAnswersReturnsDbData(){
    $user_id = 1;
    $dbPostData = getPost($user_id);
    $result = getAnswers($dbPostData[0]['id'], $user_id, $dbPostData[0]['company_id']);
    $this->assertTrue(count($result) > 0);
  }
  // getEmploymentType()
  public function testGetEmploymentTypeReturnsDbData(){
    $result = getEmploymentType();
    $this->assertTrue(count($result) > 0);
  }
  // getRatingItemsAndQuestions()
  public function testGetRatingItemsAndQuestionsReturnsDbData(){
    $result = getRatingItemsAndQuestions();
    $this->assertTrue(count($result) > 0);
  }
  // getQuestionsAndRatings()
  public function testGetQuestionsAndRatingsReturnsDbData(){
    $user_id = 1;
    $dbPostData = getPost($user_id);
    $result = getQuestionsAndRatings($dbPostData[0]['id'], $user_id, $dbPostData[0]['company_id']);
    $this->assertTrue(count($result) > 0);
  }
  // getAnswerItemsAndQuestions()
  public function testGetAnswerItemsAndQuestionsReturnsDbData(){
    $result = getAnswerItemsAndQuestions();
    $this->assertTrue(count($result) > 0);
  }
  // getQuestionsAndAnswers()
  public function getQuestionsAndAnswersReturnsDbData(){
    $user_id = 1;
    $dbPostData = getPost($user_id);
    $result = getQuestionsAndAnswers($dbPostData[0]['id'], $user_id, $dbPostData[0]['company_id']);
    $this->assertTrue(count($result) > 0);
  }
  // getCategory()
  public function testGetCategoryReturnsDbData(){
    $result = getCategory();
    $this->assertTrue(count($result) > 0);
  }
  public function testGetCategoryByIdReturnsDbData(){
    $result = getCategory(1);
    $this->assertTrue(count($result) > 0);
  }


  // =================================
  // Validation
  // =================================
  // getErrMsg()
  public function testGetErrMsgTrue(){
    global $err_msg;
    $err_msg['number'] = MSG12;
    $result = getErrMsg('number');
    $this->assertEquals($err_msg['number'], $result);
    $err_msg = array();
  }

  // validRequired()
  public function testValidRequiredTrue(){
    validRequired('abc', 'string');
    $result = getErrMsg('string');
    $this->assertNull($result);
  }
  public function testValidRequiredFalse1(){
    validRequired('', 'string');
    $result = getErrMsg('string');
    $this->assertEquals(MSG01, $result);
  }
  public function testValidRequiredFalse2(){
    validRequired(null, 'string');
    $result = getErrMsg('string');
    $this->assertEquals(MSG01, $result);
  }

  // validEmail()
  public function testvalidEmailTrue(){
    validEmail('abc@gmail.com', 'email');
    $result = getErrMsg('email');
    $this->assertNull($result);
  }
  public function testvalidEmailFalse1(){
    validEmail('abcgmail.com', 'email');
    $result = getErrMsg('email');
    $this->assertEquals(MSG02, $result);
  }
  public function testvalidEmailFalse2(){
    validEmail('abc@gmailcom', 'email');
    $result = getErrMsg('email');
    $this->assertEquals(MSG02, $result);
  }
  public function testvalidEmailFalse3(){
    validEmail('abc gmail.com', 'email');
    $result = getErrMsg('email');
    $this->assertEquals(MSG02, $result);
  }

  // validNumber()
  public function testValidNumberTrue(){
    validNumber('111', 'number');
    $result = getErrMsg('number');
    $this->assertNull($result);
  }
  public function testValidNumberFalse1(){
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

}