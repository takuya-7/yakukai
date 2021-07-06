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
  // getCompanyRanking()
  public function testGetCompanyRankingReturnsDbData(){
    $result = getCompanyRanking();
    $this->assertTrue(count($result) > 0);
  }
  // getCompanyOne()
  public function testGetCompanyOneReturnsDbData(){
    $result = getCompanyOne(1);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetCompanyOneReturnsFalse(){
    $result = getCompanyOne(0);
    $this->assertEmpty($result['info']);
  }
  // getCompanyList()
  public function testGetCompanyListReturnsDbData(){
    $currentPageNum = 1;
    $listSpan = 20;
    $currentMinNum = ($currentPageNum - 1) * $listSpan;
    $companyName = '日本';
    $prefecture = '';
    $industry = '';
    $sort = 1;
    $result = getCompanyList($currentMinNum, $companyName, $prefecture, $industry, $sort, $listSpan);
    $this->assertTrue(count($result) > 0);
  }
  // getCompanyRatings()
  public function testGetCompanyRatingsReturnsDbData(){
    $result = getCompanyRatings(1);
    $this->assertTrue(count($result) > 0);
  }
  // getTopAnswer()
  public function testGetTopAnswerReturnsDbData(){
    $result = getTopAnswer(1);
    $this->assertTrue(count($result) > 0);
  }
  public function testGetTopAnswerReturnsFalse(){
    $result = getTopAnswer(0);
    $this->assertEmpty($result);
  }
  // getPickUpPosts()
  public function testGetPickUpPostsReturnsDbData(){
    $result = getPickUpPosts(1,1);
    $this->assertTrue(count($result) > 0);
  }
  // getPostByCategory()
  public function testGetPostByCategoryReturnsDbData(){
    $result = getPostByCategory(1,1);
    $this->assertTrue(count($result) > 0);
  }
  // getPrefecture()
  public function testGetPrefectureReturnsDbData(){
    $result = getPrefecture();
    $this->assertTrue(count($result) > 0);
  }
  // isGood()
  public function testIsGoodReturnsDbData(){
    try{
      $dbh = dbConnect();
      $sql = 'SELECT * FROM good LIMIT 1';
      $data = array();
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        $dbGoodData = $stmt->fetch(PDO::FETCH_ASSOC);
      }
    }catch (Exception $e){
      error_log('エラー発生：'.$e->getMessage());
    }
    $result = isGood($dbGoodData['user_id'], $dbGoodData['answer_id']);
    $this->assertTrue($result);
  }
  // getGoodCount()
  public function testGetGoodCountReturnsDbData(){
    try{
      $dbh = dbConnect();
      $sql = 'SELECT * FROM good LIMIT 1';
      $data = array();
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        $dbGoodData = $stmt->fetch(PDO::FETCH_ASSOC);
      }
    }catch (Exception $e){
      error_log('エラー発生：'.$e->getMessage());
    }
    $result = getGoodCount($dbGoodData['answer_id']);
    $this->assertTrue($result > 0);
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
  // validEmailDup()
  public function testValidEmailDupReturnsErr(){
    global $err_msg;
    $err_msg = array();
    try{
      $dbh = dbConnect();
      $sql = 'SELECT email FROM users WHERE delete_flg = 0 LIMIT 1';
      $data = array();
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        $dbUserData = $stmt->fetch(PDO::FETCH_ASSOC);
      }
    }catch (Exception $e){
      error_log('エラー発生：'.$e->getMessage());
    }
    validEmailDup($dbUserData['email']);
    $result = getErrMsg('email');
    $this->assertEquals(MSG08, $result);
  }
  public function testValidEmailDupReturnsNull(){
    global $err_msg;
    $err_msg = array();
    validEmailDup('abcde@abcde.abcde');
    $this->assertEquals(array(), $err_msg);
  }
  // validMatch()
  public function testValidMatchTrue(){
    global $err_msg;
    $err_msg = array();
    validMatch('abc', 'abc', 'password');
    $this->assertEquals(array(), $err_msg);
  }
  public function testValidMatchFalse(){
    global $err_msg;
    $err_msg = array();
    validMatch('abc', 'abcd', 'password');
    $this->assertEquals(MSG03, $err_msg['password']);
  }
  // validMinLen()
  public function testValidMinLenTrue(){
    global $err_msg;
    $err_msg = array();
    validMinLen('password', '123456');
    $this->assertEquals(array(), $err_msg);
  }
  public function testValidMinLenFalse(){
    global $err_msg;
    $err_msg = array();
    validMinLen('12345', 'password');
    $this->assertEquals(MSG05, $err_msg['password']);
  }
  // validMaxLen()
  public function testValidMaxLenTrue(){
    global $err_msg;
    $err_msg = array();
    validMaxLen('123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345', 'comment');
    $this->assertEquals(array(), $err_msg);
  }
  public function testValidMaxLenFalse(){
    global $err_msg;
    $err_msg = array();
    validMaxLen('1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456', 'comment');
    $this->assertEquals(MSG06, $err_msg['comment']);
  }
  // validHalf()
  public function testValidHalfTrue(){
    global $err_msg;
    $err_msg = array();
    validHalf('abc123', 'name');
    $this->assertEquals(array(), $err_msg);
  }
  public function testValidHalfFalse(){
    global $err_msg;
    $err_msg = array();
    validHalf('あいう１２３', 'name');
    $this->assertEquals(MSG04, $err_msg['name']);
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
  // validPass()
  public function testValidPassTrue(){
    global $err_msg;
    $err_msg = array();
    validPass('123456', 'password');
    $this->assertEquals(array(), $err_msg);
  }
  public function testValidPassFalse1(){
    global $err_msg;
    $err_msg = array();
    validPass('１２３４５６', 'password');
    $this->assertEquals(MSG04, $err_msg['password']);
  }
  public function testValidPassFalse2(){
    global $err_msg;
    $err_msg = array();
    validPass('12345', 'password');
    $this->assertEquals(MSG05, $err_msg['password']);
  }
  public function testValidPassFalse3(){
    global $err_msg;
    $err_msg = array();
    validPass('1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456', 'password');
    $this->assertEquals(MSG06, $err_msg['password']);
  }

  //================================
  // Session, Login
  //================================
  // getSessionFlash()
  public function testGetSessionFlashTrue(){
    $_SESSION['message'] = 'message';
    $result = getSessionFlash('message');
    $this->assertEquals('message', $result);
  }
  // isLogin()
  public function testIsLoginTrue(){
    $_SESSION['login_date'] = time();
    $result = isLogin();
    $this->assertTrue($result);
  }
  public function testIsLoginFalse(){
    $_SESSION['login_date'] = time() - 60*61;
    $_SESSION['login_limit'] = 60*60;
    $result = isLogin();
    $this->assertFalse($result);
  }

  //================================
  // Date
  //================================
  // getYearDiff()
  public function testGetYearDiffTrue(){
    $result = getYearDiff('2021-7-6 15:55:00', '2020-7-6 15:55:00');
    $this->assertEquals(1, $result);
  }

  //================================
  // Other
  //================================
  // sanitize()
  public function testSanitizeTrue(){
    $result = sanitize('"abc');
    $this->assertEquals('&quot;abc', $result);
  }
  // getFormData()
  public function testGetFormDataReturnsErrPost(){
    $_POST['name'] = '';
    validRequired($_POST['name'], 'name');
    $result = getFormData('name');
    $this->assertEquals('', $result);
    $err_msg = array();
  }
  public function testGetFormDataReturnsPost(){
    $_POST['name'] = 'tarou';
    validRequired($_POST['name'], 'name');
    $result = getFormData('name');
    $this->assertEquals('tarou', $result);
    $_POST = null;
  }
  public function testGetFormDataReturnsDbData(){
    $_POST['name'] = null;
    global $dbFormData;
    $dbFormData['name'] = 'ichiro';
    $result = getFormData('name');
    $this->assertEquals('ichiro', $result);
  }
  public function testGetFormDataReturnsPostOnNullDb(){
    $_POST['name'] = 'tarou';
    global $dbFormData;
    $dbFormData['name'] = '';
    $result = getFormData('name');
    $this->assertEquals('tarou', $result);
  }
  // appendGetParam()
  public function testAppendGetParamTrue(){
    $_GET = array('a'=>1, 'b'=>2, 'c'=>3);
    $result = appendGetParam(array('a', 'b'));
    $this->assertEquals('?c=3', $result);
  }
}