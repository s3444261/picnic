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

class UserTest extends PicnicTestCase {

	const USER_ID       = 'userID';
	const USER 			= 'user';
	const EMAIL         = 'email';
	const PASSWORD 		= 'password';
	const STATUS 		= 'status';
	const ACTIVATE 		= 'activate';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	const MODULE 		= 'picnic';

	const CORRECT_USER = 'peter';
	const CORRECT_EMAIL_ADDRESS = 'peter@gmail.com';
	const CORRECT_PASSWORD = 'TestTest88';
	const WRONG_USER = 'john';
	const WRONG_EMAIL_ADDRESS = 'john@hotmail.com';
	const WRONG_PASSWORD = 'TestTest99';
	const USER_TWO = 'mary';
	const EMAIL_ADDRESS_TWO = 'mary@gmail.com';
	const PASSWORD_TWO = 'TestTest77';
	const USER_THREE = 'adrian';
	const EMAIL_ADDRESS_THREE = 'adrian@gmail.com';
	const PASSWORD_THREE = 'TestTest66';

	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);

		$args = [
				self::USER 			=> self::CORRECT_USER,
				self::EMAIL 		=> self::CORRECT_EMAIL_ADDRESS,
				self::PASSWORD		=> self::CORRECT_PASSWORD,
				self::STATUS 		=> 'active'
		];
		
		$args1 = [
			self::USER 			=> self::USER_TWO,
			self::EMAIL 		=> self::EMAIL_ADDRESS_TWO,
			self::PASSWORD		=> self::PASSWORD_TWO,
			self::STATUS 		=> 'active'
		];
		
		$args2 = [
				self::USER 			=> self::USER_THREE,
				self::EMAIL 		=> self::EMAIL_ADDRESS_THREE,
				self::PASSWORD		=> self::PASSWORD_THREE,
				self::STATUS 		=> 'active'
		];
		
		$u = new User($pdo, $args);
		try {
			$u->set();
		} catch (UserException $e) {
			// Do Nothing
		}
		try {
			$u->get();
		} catch (UserException $e) {
			// Do Nothing
		}
		$u->activate();
		
		for($i = 1; $i < 3; $i++){
			${'u' . $i} = new User($pdo, ${'args' . $i});
			try {
				${'u' . $i}->set();
			} catch (UserException $e) {
				// Do Nothing
			}
			try {
				${'u' . $i}->get();
			} catch (UserException $e) {
				// Do Nothing
			}
			if($i < 2){
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

	public function testSetResultsInValidId(): void {
		$sut = new User(TestPDO::getInstance(), [self::USER_ID => 1, self::USER => 'someUser', self::EMAIL => 'test@address.net', self::PASSWORD => 'TestTest88']);
		try {
			$returnedId = $sut->set();
		} catch (UserException $e) {
			//Do nothing
		}
		$this->assertGreaterThan(0, $returnedId);
		$this->assertGreaterThan(0, $sut->{self::USER_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		$u = new User(TestPDO::getInstance(), [self::USER_ID => 1, self::USER => 'someUser', self::EMAIL => 'test@address.net', self::PASSWORD => 'TestTest88']);
		try {
			$u->set(); 
		} catch (UserException $e) {
			//Do nothing
		}
		$sut = new User(TestPDO::getInstance(), [self::USER_ID => 1, self::USER => 'someUser', self::EMAIL => 'test@address.net', self::PASSWORD => 'TestTest88']);
		try {
			$returnedId = $sut->set(); 
		} catch (UserException $e) {
			//Do nothing
		}
		$this->assertEquals(0, $returnedId);
		$this->assertEquals(1, $sut->{self::USER_ID});
	}

	public function testUpdateUserNameSucceeds(): void
	{
		$u = new User(TestPDO::getInstance(), [self::USER_ID => 1, self::USER => 'someUser', self::EMAIL => 'test@address.net', self::PASSWORD => 'TestTest88']);
		try {
			$u->set();
		} catch (UserException $e) {
			//Do nothing
		} 
		
		$NEW_USER_NAME = 'NewUserName';

		$updating = $this->createSutWithId(1); 
		try {
			$updating->get(); 
		} catch (UserException $e) {
			// Do Nothing
		}
		
		$updating->user = $NEW_USER_NAME;
		try {
			$updating->update();
		} catch (UserException $e) {
			// Do Nothing
			echo $e->getMessage();
		}
		
		try {
			$updating->get(); 
		} catch (UserException $e) {
			// Do Nothing
		}
		
		$sut = $this->createSutWithId(1);
		try {
			$sut->get(); 
		} catch (UserException $e) {
			// Do Nothing
		}
		
		$this->assertEquals($NEW_USER_NAME, $sut->user);
	}

	public function testUpdateEmailAddressSucceeds(): void
	{
		$u = new User(TestPDO::getInstance(), [self::USER_ID => 1, self::USER => 'someUser', self::EMAIL => 'test@address.net', self::PASSWORD => 'TestTest88']);
		try {
			$u->set();
		} catch (UserException $e) {
			//Do nothing
		} 
		
		$NEW_EMAIL_ADDRESS = 'new@gmail.com';

		$updating = $this->createSutWithId(1);
		try {
			$updating->get(); 
		} catch (UserException $e) {
			// Do Nothing
		}
		$updating->email = $NEW_EMAIL_ADDRESS;
		try {
			$updating->update();
		} catch (UserException $e) {
			// Do Nothing
		}
		try {
			$updating->get(); 
		} catch (UserException $e) {
			// Do Nothing
		}

		$sut = $this->createSutWithId(1);
		try {
			$sut->get();
		} catch (UserException $e) {
			// Do Nothing
		}

		$this->assertEquals($NEW_EMAIL_ADDRESS, $sut->email);
	}

	public function testUpdatePasswordSucceeds(): void
	{
		$u = new User(TestPDO::getInstance(), [self::USER_ID => 1, self::USER => 'someUser', self::EMAIL => 'test@address.net', self::PASSWORD => 'TestTest88']);
		try {
			$u->set();
		} catch (UserException $e) {
			//Do nothing
		} 
		
		$updating = $this->createSutWithId(1);
		try {
			$updating->get();
		} catch (UserException $e) {
			// Do Nothing
		}
		$PREVIOUS_PASSWORD = $updating->password;

		$updating->password = 'NewPassword99';
		try {
			$updating->update();
		} catch (UserException $e) {
			// Do Nothing
		}

		$sut = $this->createSutWithId(1);
		try {
			$sut->get();
		} catch (UserException $e) {
			// Do Nothing
		}

		$this->assertNotEquals($PREVIOUS_PASSWORD, $sut->password);
	}

	public function testUpdateStatusSucceeds(): void
	{
		$NEW_STATUS = 'NewStatus';
		
		$u = new User(TestPDO::getInstance(), [self::USER_ID => 1, self::USER => 'someUser', self::EMAIL => 'test@address.net', self::PASSWORD => 'TestTest88']);
		try {
			$u->set();
		} catch (UserException $e) {
			//Do nothing
		}

		$updating = $this->createSutWithId(1);
		try {
			$updating->get();
		} catch (UserException $e) {
			// Do Nothing
		}
		$updating->status = $NEW_STATUS;
		try {
			$updating->update();
		} catch (UserException $e) {
			// Do Nothing
		}

		$sut = $this->createSutWithId(1);
		try {
			$sut->get();
		} catch (UserException $e) {
			// Do Nothing
		}

		$this->assertEquals($NEW_STATUS, $sut->status);
	}

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
	
	public function testSuccessfulCheckUserExist(): void {
		$sut = $this->createDefaultSut();
		$sut->user = self::WRONG_USER;
		$this->assertFalse($sut->checkUserExist());
		
		$sut->user = self::USER_TWO;
		$this->assertTrue($sut->checkUserExist());
	}
	
	public function testSuccessfulCheckEmailExist(): void {
		$sut = $this->createDefaultSut();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		$this->assertFalse($sut->checkEmailExist());
		
		$sut->email = self::EMAIL_ADDRESS_TWO;
		$this->assertTrue($sut->checkEmailExist());
	}
}
