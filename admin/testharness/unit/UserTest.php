<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

declare(strict_types=1);

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname(__FILE__) . '/../../createDB/DatabaseGenerator.php';
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/UserException.php';
require_once dirname(__FILE__) . '/../../../model/Validation.php';
require_once dirname(__FILE__) . '/../../../model/ValidationException.php';

class UserTest extends PHPUnit\Framework\TestCase {

	const USER_ID       = 'userID';
	const USER 			= 'user';
	const EMAIL         = 'email';
	const PASSWORD 		= 'password';
	const STATUS 		= 'status';
	const ACTIVATE 		= 'activate';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	const MODULE 		= 'picnic';
	
	const USER_ID_1 = 1;
	const USER_ONE = 'peter';
	const EMAIL_ADDRESS_ONE = 'peter@gmail.com';
	const PASSWORD_ONE = 'TestTest88';
	const USER_ID_2 = 2;
	const USER_TWO = 'mary';
	const EMAIL_ADDRESS_TWO = 'mary@gmail.com';
	const PASSWORD_TWO = 'TestTest77';
	const USER_ID_3 = 3;
	const USER_THREE = 'adrian';
	const EMAIL_ADDRESS_THREE = 'adrian@gmail.com';
	const PASSWORD_THREE = 'TestTest66';
	const USER_ADD = 'brian';
	const EMAIL_ADD = 'brian@gmail.com';
	const PASSWORD_ADD = 'TestTest88';
	const USER_SHORT = 'bri';
	const EMAIL_BAD = 'brian.gmail.com';
	const PASSWORD_SHORT = 'Test88';
	const PASSWORD_NO_UPPER = 'testtest88';
	const PASSWORD_NO_LOWER = 'TESTTEST88';
	const PASSWORD_NO_NUMBER = 'TestTestTest';
	const FAILED_TO_ADD_USER = 'Failed to add User.';
	const ERROR_USER_NOT_EXIST = 'User does not exist!';
	const ERROR_USER_EMPTY = 'Username Error: Input is required!';
	const ERROR_USER_SHORT = 'Username Error: Input must be atleast 4 characters in length!';
	const ERROR_USER_DUPLICATE = 'This user name is not available!';
	const ERROR_EMAIL_EMPTY = 'Email Error: Input is required!';
	const ERROR_EMAIL_INVALID = 'Email Error: Email address must be valid!';
	const ERROR_EMAIL_DUPLICATE = 'This email address is not available!';
	const ERROR_PASSWORD_EMPTY = 'Password Error: Input is required!';
	const ERROR_PASSWORD_INVALID = 'Password Error: Atleast one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!';
	const INVALID_ID = 200;
	const INVALID_ACTIVATION_CODE = 'ef4flslerlwldxl234lsdl3w';
	const ERROR_ACTIVATION_CODE = 'Failed to retrieve UserID!';
	const WRONG_USER = 'john';
	const WRONG_EMAIL_ADDRESS = 'john@hotmail.com';
	const WRONG_PASSWORD = 'TestTest99';
	const STATUS_ADD = 'suspended';

	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);
		$args1 = [
				self::USER 			=> self::USER_ONE,
				self::EMAIL 		=> self::EMAIL_ADDRESS_ONE,
				self::PASSWORD		=> self::PASSWORD_ONE
		];
		
		$args2 = [
				self::USER 			=> self::USER_TWO,
				self::EMAIL 		=> self::EMAIL_ADDRESS_TWO,
				self::PASSWORD		=> self::PASSWORD_TWO
		];
		
		$args3 = [
				self::USER 			=> self::USER_THREE,
				self::EMAIL 		=> self::EMAIL_ADDRESS_THREE,
				self::PASSWORD		=> self::PASSWORD_THREE
		];
		
		// Populate the Users table.
		
		for($i = 1; $i < 4; $i++){
			${'u' . $i} = new User($pdo, ${'args' . $i});
			try {
				${'u' . $i}->set();
			} catch (UserException $e) { }
			try {
				${'u' . $i}->get();
			} catch (UserException $e) { }
			if($i != 3){
				${'u' . $i}->activate();
			}
		}
		$_SESSION = array();
	}

	protected function createDefaultSut() {
		return new User(TestPDO::getInstance());
	}

	protected function createSutWithId($id) {
		return new User(TestPDO::getInstance(), [self::USER_ID => $id]);
	}

	protected function getValidId() {
		return 1;
	}

	protected function getInvalidId() {
		return 200;
	}

	protected function getExpectedExceptionTypeForUnknownId() {
		return UserException::class;
	}
	
	protected function getExpectedExceptionTypeForUnsetId() {
		return UserException::class;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::USER_ID 		=> 1,
			self::USER 			=> 'peter',
			self::EMAIL 		=> 'peter@gmail.com',
			self::PASSWORD		=> '613b59f86203d9e986e08514d175a7d690f3b8f9',
			self::STATUS 		=> 'active',
			self::ACTIVATE 		=> null
		];
	}
	
	public function testAttributes(): void {
		$values = [
			self::USER_ID 		=> 1,
			self::USER 			=> 'grant',
			self::EMAIL 		=> 'grant@kinkead.net',
			self::PASSWORD		=> 'TestTest88',
			self::STATUS 		=> 'active',
			self::ACTIVATE 		=> 'blahblahblah',
			self::CREATION_DATE => '1984-08-18',
			self::MODIFIED_DATE => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}
	
	/*
	 * Set() Tests
	 */
	
	public function testSetNoUser(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => NULL, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->expectExceptionMessage(self::ERROR_USER_EMPTY);
		$sut->set();
	}
	
	public function testSetShortUser(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_SHORT, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->expectExceptionMessage(self::ERROR_USER_SHORT);
		$sut->set();
	}
	
	public function testSetExistUser(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_TWO, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->expectExceptionMessage(self::ERROR_USER_DUPLICATE);
		$sut->set();
	}
	
	public function testSetNoEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => NULL, self::PASSWORD => self::PASSWORD_ADD]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_EMPTY);
		$sut->set();
	}
	
	public function testSetBadEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_BAD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_INVALID);
		$sut->set();
	}
	
	public function testSetExistEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADDRESS_TWO, self::PASSWORD => self::PASSWORD_ADD]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_DUPLICATE);
		$sut->set();
	}
	
	public function testSetNoPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => NULL]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_EMPTY);
		$sut->set();
	}
	
	public function testSetShortPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_SHORT]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_INVALID);
		$sut->set();
	}
	
	public function testSetNoUpperPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_UPPER]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_INVALID);
		$sut->set();
	}
	
	public function testSetNoLowerPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_LOWER]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_INVALID);
		$sut->set();
	}
	
	public function testSetNoNumberPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_NUMBER]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_INVALID);
		$sut->set();
	}
	
	public function testSetSuccessful(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$this->assertTrue($sut->set());
		} catch (Exception $e) {
			$this->assertFalse(true);
		}
	}
	
	/*
	 * getUserIdByActivationCode() Test
	 */
	
	public function testGetUserIdByActivationCodeInvalidCode(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->set();
			$sut->userID = $sut->get();
		} catch (UserException $e) {}
		$sut->activate = self::INVALID_ACTIVATION_CODE;
		$this->expectExceptionMessage('Failed to retrieve UserID!');
		$sut->getUserIdByActivationCode();
	}
	
	public function testGetUserIdByActivationCodeValidCode(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->set();
			$sut->userID = $sut->get();
		} catch (UserException $e) {}
		$this->assertGreaterThan(0, $sut->getUserIdByActivationCode());
	}
	
	public function testActivateAccountInvalidId(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->userID = $sut->set();
		} catch (UserException $e) {
			$this->assertFalse(true);
		}
		try {
			$sut->get();
		} catch (UserException $e) {
			$this->assertFalse(true);
		}
		$sut->userID = self::INVALID_ID;
		$this->assertFalse($sut->activate());
	}
	
	/*
	 * activateAccount() Test
	 */
	
	public function testActivateAccountValidId(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->userID = $sut->set();
		} catch (UserException $e) {
			$this->assertFalse(true);
		}
		try {
			$sut->get();
		} catch (UserException $e) {
			$this->assertFalse(true);
		}
		$this->assertTrue($sut->activate());
	}
	
	/*
	 * Test updatePassword()
	 */
	
	public function testUpdatePasswordNoPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => NULL]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_EMPTY);
		$sut->updatePassword();
	}
	
	public function testUpdatePasswordShortPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_SHORT]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_INVALID);
		$sut->updatePassword();
	}
	
	public function testUpdatePasswordNoUpperPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_NO_UPPER]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_INVALID);
		$sut->updatePassword();
	}
	
	public function testUpdatePasswordNoLowerPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_NO_LOWER]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_INVALID);
		$sut->updatePassword();
	}
	
	public function testUpdatePasswordNoNumberPassword(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_NO_NUMBER]);
		$this->expectExceptionMessage(self::ERROR_PASSWORD_INVALID);
		$sut->updatePassword();
	}
	
	public function testUpdatePasswordSuccessful(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => 1, self::PASSWORD => self::PASSWORD_ADD]);
		try {
			$sut->get();
		} catch (UserException $e) {}
		$oldPassword = $sut->password;
		$sut->password = self::PASSWORD_ADD;
		$this->assertTrue($sut->updatePassword());
		try {
			$sut->get();
		} catch (UserException $e) {}
		$newPassword = $sut->get()->password;
		if(strlen($oldPassword) > 0 && strlen($newPassword) > 0 && $oldPassword != $newPassword){
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
	}
	
	/*
	 * Test forgotPassword()
	 */
	
	public function testGetUserIdByEmailNoEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::EMAIL => NULL]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_EMPTY);
		$sut = $sut->getUserIdByEmail($sut);
	}
	
	public function testGetUserIdByEmailBadEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::EMAIL => self::EMAIL_BAD]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_INVALID);
		$sut = $sut->getUserIdByEmail($sut);
	}
	
	public function testGetUserIdByEmailNotExistEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::EMAIL => self::EMAIL_BAD]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_INVALID);
		$sut = $sut->getUserIdByEmail($sut);
	}
	
	public function testGetUserIdByEmailExistEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::EMAIL => self::EMAIL_ADDRESS_TWO]);
		$sut->userID = $sut->getUserIdByEmail($sut);
		$this->assertEquals(2, $sut->userID);
	}
	
	public function testSuccessfulActivate(): void {
		$sut = $this->createSutWithId(3);
		try {
			$sut->get();
		} catch (Exception $e) {
			// Do Nothing
		}
		$this->assertEquals(32, strlen($sut->activate));
		$sut->activate();
		try {
			$sut->get();
		} catch (Exception $e) {
			// Do Nothing
		}
		$this->assertNull($sut->activate);
	}
	
	public function testSuccessfulCheckPassword(): void {
		$sut = $this->createDefaultSut();
		$sut->email = self::EMAIL_ADDRESS_TWO;
		$sut->password = self::PASSWORD_THREE;
		$this->assertFalse($sut->checkPassword());
		
		$sut->password = self::PASSWORD_TWO;
		$this->assertTrue($sut->checkPassword());
	}
	
	public function testSuccessfulGetRandomPassword(): void {
		$sut = $this->createDefaultSut();
		$this->assertEquals(10, strlen($sut->getRandomPassword()));
	}
	
	public function testCheckUserExistFalse(): void {
		$sut = $this->createDefaultSut();
		$sut->user = self::WRONG_USER;
		$this->expectExceptionMessage(self::ERROR_USER_DUPLICATE);
		$sut->checkUserExist();
	}
	
	public function testCheckUserExistTrue(): void {
		$sut = $this->createDefaultSut();
		$sut->user = self::USER_TWO;
		$this->assertTrue($sut->checkUserExist());
	}
	
	public function testCheckEmailExistFalse(): void {
		$sut = $this->createDefaultSut();
		$sut->user = self::WRONG_EMAIL;
		$this->expectExceptionMessage(self::ERROR_EMAIL_DUPLICATE);
		$sut->checkEmailExist();
	}
	
	public function testCheckEmailExistTrue(): void {
		$sut = $this->createDefaultSut();
		$sut->email = self::EMAIL_TWO;
		$this->assertTrue($sut->checkEmailExist());
	}
	
	/*
	 * UpdateUser() Tests
	 */
	
	public function testUpdateUserNoUserID(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->expectExceptionMessage(self::ERROR_USER_NOT_EXIST);
		$system->updateUser($sut);
	}
	
	public function testUpdateUserNoUser(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => NULL, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->expectExceptionMessage(self::ERROR_USER_EMPTY);
		$system->updateUser($sut);
	}
	
	public function testUpdateUserShortUser(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_SHORT, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->expectExceptionMessage(self::ERROR_USER_SHORT);
		$system->updateUser($sut);
	}
	
	public function testUpdateUserExistUser(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_TWO, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->expectExceptionMessage(self::ERROR_USER_DUPLICATE);
		$system->updateUser($sut);
	}
	
	public function testUpdateUserNoEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => NULL, self::STATUS => self::STATUS_ADD]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_EMPTY);
		$system->updateUser($sut);
	}
	
	public function testUpdateUserBadEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_BAD, self::STATUS => self::STATUS_ADD]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_INVALID);
		$system->updateUser($sut);
	}
	
	public function testUpdateUserExistEmail(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADDRESS_TWO, self::STATUS => self::STATUS_ADD]);
		$this->expectExceptionMessage(self::ERROR_EMAIL_DUPLICATE);
		$system->updateUser($sut);
	}
	
	public function testUpdateUserNoStatus(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->assertTrue($sut->updateUser($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertEquals(self::STATUS_ADD, $sut->status);
	}
	
	public function testUpdateUserSuccessful(): void{
		$pdo = TestPDO::getInstance();
		$sut = new User($pdo, [self::USER_ID => self::USER_ID_1, self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::STATUS => self::STATUS_ADD]);
		$this->assertTrue($sut->updateUser($sut));
		try {
			$sut->get();
		} catch (Exception $e) {}
		$this->assertEquals(self::USER_ADD, $sut->user);
		$this->assertEquals(self::EMAIL_ADD, $sut->email);
		$this->assertEquals(self::STATUS_ADD, $sut->status);
	}
	
	public function testSuccessfulCountUser(): void {
		$sut = $this->createDefaultSut();
		$sut->user = self::USER_TWO;
		$this->assertEquals(1, $sut->countUser());
	}
	
	public function testSuccessfulCountEmail(): void {
		$sut = $this->createDefaultSut();
		$sut->email = self::EMAIL_ADDRESS_THREE;
		$this->assertEquals(1, $sut->countEmail());
	}
/*
	public function testLogoutClearsSessionData() : void {
		
		$sut = $this->createDefaultSut();
		$sut->email = self::CORRECT_EMAIL_ADDRESS;
		$sut->password = self::CORRECT_PASSWORD;
		try {
			$sut->login();
		} catch (UserException $e) {
			// Do Nothing
		}
		
		$this->assertNotEmpty($_SESSION);

		$sut->logout();

		$this->assertEmpty($_SESSION);
	}

	public function testLoginFailureDoesNotCreateSessionData(): void {
		
		unset($_SESSION[self::MODULE]);
		
		$sut = $this->createDefaultSut();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		$sut->password = self::WRONG_PASSWORD;

		try {
			$sut->login();
		} catch (UserException $e) {
			// Do Nothing
		}

		$this->assertEmpty($_SESSION);
	}

	public function testLoginFailsForIncorrectEmail(): void {
		
		unset($_SESSION[self::MODULE]);
		
		$sut = $this->createDefaultSut();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		$sut->password = self::CORRECT_PASSWORD;
		
		try {
			$bool = $sut->login();
		} catch (UserException $e) {
			$bool = false;
		}
		$this->assertFalse($bool);
	}

	public function testLoginFailsForIncorrectPassword(): void {
		
		unset($_SESSION[self::MODULE]);

		$sut = $this->createDefaultSut();
		$sut->email = self::CORRECT_EMAIL_ADDRESS;
		$sut->password = self::WRONG_PASSWORD;

		try {
			$bool = $sut->login();
		} catch (UserException $e) {
			$bool = false;
		}
		$this->assertFalse($bool);
	}

	public function testLoginFailsForAccountNotActivated(): void {
		
		$u = new User(TestPDO::getInstance(), [self::USER => 'johnny', self::EMAIL => 'john@gmail.com', self::PASSWORD => 'TestTest99']);
		try {
			$u->set();
			$u->get();
		} catch (UserException $e) {
			//Do nothing
		}
		
		unset($_SESSION[self::MODULE]);
		
		$sut = $this->createSutWithId(2);
		$sut->email = 'john@gmail.com';
		$sut->password = 'TestTest99';  

		try {
			$bool = $sut->login();
		} catch (UserException $e) {
			$bool = false;
		}
		$this->assertFalse($bool);
	}

	public function testLoginFailsForAccountSuspended(): void {
		
		$u = $this->createSutWithId(1);
		$u->status = 'suspended';
		try {
			$u->update();
		} catch (UserException $e) {
			// Do Nothing
		}
		
		unset($_SESSION[self::MODULE]);
		
		$sut = $this->createSutWithId(1);
		$sut->email = 'peter@gmail.com';
		$sut->password = 'TestTest88';
		
		try {
			$bool = $sut->login();
		} catch (UserException $e) {
			$bool = false;
		}
		$this->assertFalse($bool);
	}

	public function testLoginSucceedsForValidEmailAndPasswordAndActivated(): void {
				
		unset($_SESSION[self::MODULE]);
		
		$sut = $this->createSutWithId(1);
		$sut->email = 'peter@gmail.com';
		$sut->password = 'TestTest88';
		
		try {
			$bool = $sut->login();
		} catch (UserException $e) {
			$bool = false;
		}
		$this->assertTrue($bool);
	}

	public function testSuccessfulLoginCreatesSessionData(): void {
		$this->assertEmpty($_SESSION);

		$sut = $this->createDefaultSut();
		$sut->email = self::CORRECT_EMAIL_ADDRESS;
		$sut->password = self::CORRECT_PASSWORD;
		try {
			$sut->login();
		} catch (UserException $e) {
			// Do Nothing
		}
		
		$this->assertNotEmpty($_SESSION);
	} 
	
	
	
	
	
	public function testSuccessfulGetUsers(): void {
		$sut = $this->createDefaultSut();
		$users = $sut->getUsers();
		$i = 1;
		foreach($users as $user){
			if($i == 1 ){
				$this->assertEquals($user['userID'], $i);
				$this->assertEquals($user['user'], self::CORRECT_USER);
				$this->assertEquals($user['email'], SELF::CORRECT_EMAIL_ADDRESS);
				$this->assertEquals($user['status'], 'active');
			} else if($i == 1 ){
				$this->assertEquals($user['userID'], $i);
				$this->assertEquals($user['user'], self::USER_TWO);
				$this->assertEquals($user['email'], SELF::EMAIL_ADDRESS_TWO);
				$this->assertEquals($user['status'], 'active');
			} else if($i == 1 ){
				$this->assertEquals($user['userID'], $i);
				$this->assertEquals($user['user'], self::USER_THREE);
				$this->assertEquals($user['email'], SELF::EMAIL_ADDRESS_THREE);
				$this->assertEquals($user['status'], 'active');
			} 
			$i++;
		}
	}
	*/
	
}
