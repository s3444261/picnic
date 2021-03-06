<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

/*
 * TEST SUMMARY
 *
 * -- User.php Test Blocks: --
 * get(): User
 * getUserIdByEmail(): int
 * set(): int
 * update(): bool
 * updatePassword(): bool
 * delete(): bool
 * exists(): bool
 * countUser(): int
 * countEmail(): int
 * getUserIdByActivationCode(): bool
 * activate(): bool
 * getUsers(): array
 * login(): bool
 * checkPassword(): bool
 * getRandomPassword(): string
 * checkUserExist(): bool
 * checkEmailExist(): bool
 *
 * -- User.php Test SubBlocks: --
 * get(): User
 * -- testGetUserNoUserId(): void
 * -- testGetUserInvalidUserId(): void
 * -- testGetUserValidUserId(): void
 *
 * getUserIdByEmail(): int
 * -- testGetUserIdByEmailNoEmail(): void
 * -- testGetUserIdByEmailBadEmail(): void
 * -- testGetUserIdByEmailNotExistEmail(): void
 * -- testGetUserIdByEmailExistEmail(): void
 *
 * set(): int
 * -- testSetNoUser(): void
 * -- testSetShortUser(): void
 * -- testSetExistUser(): void
 * -- testSetNoEmail(): void
 * -- testSetBadEmail(): void
 * -- testSetExistEmail(): void
 * -- testSetNoPassword(): void
 * -- testSetShortPassword(): void
 * -- testSetNoUpperPassword(): void
 * -- testSetNoLowerPassword(): void
 * -- testSetNoNumberPassword(): void
 * -- testSetSuccessful(): void
 *
 * update(): bool
 * -- testUpdateUserNoUserID(): void
 * -- testUpdateUserNoUser(): void
 * -- testUpdateUserShortUser(): void
 * -- testUpdateUserExistUser(): void
 * -- testUpdateUserNoEmail(): void
 * -- testUpdateUserBadEmail(): void
 * -- testUpdateUserExistEmail(): void
 * -- testUpdateUserNoStatus(): void
 * -- testUpdateUserSuccessful(): void
 *
 * updatePassword(): bool
 * -- testUpdatePasswordNoPassword(): void
 * -- testUpdatePasswordShortPassword(): void
 * -- testUpdatePasswordNoUpperPassword(): void
 * -- testUpdatePasswordNoLowerPassword(): void
 * -- testUpdatePasswordNoNumberPassword(): void
 * -- testUpdatePasswordSuccessful(): void
 *
 * delete(): bool
 * -- testDeleteNoUserID(): void
 * -- testDeleteInvalidUserID(): void
 * -- testDeleteValidUserID(): void
 *
 * exists(): bool
 * -- testExistsNoUserID(): void
 * -- testExistsInvalidUserID(): void
 * -- testExistsValidUserID(): void
 *
 * countUser(): int
 * -- testCountNoUser(): void
 * -- testCountUserShort(): void
 * -- testCountUserNotAlpha(): void
 * -- testCountValidUser(): void
 *
 * countEmail(): int
 * -- testCountEmailNoEmail(): void
 * -- testCountEmailInvalid(): void
 * -- testCountEmailValid(): void
 *
 * getUserIdByActivationCode(): bool
 * -- testGetUserIdByActivationCodeEmpty(): void
 * -- testGetUserIdByActivationCodeIncorrectLength(): void
 * -- testGetUserIdByActivationCodeInvalidCode()
 * -- testGetUserIdByActivationCodeValidCode(): void
 *
 * activate(): bool
 * -- testActivateNoUserID(): void
 * -- testActivateInvalidUserID(): void
 * -- testActivateValidUserID(): void
 *
 * getUsers(): array
 * -- testGetUsersSuccess(): void
 *
 * login(): bool
 * -- testLogoutClearsSessionData() : void
 * -- testLoginFailureDoesNotCreateSessionData(): void
 * -- testLoginFailsForIncorrectEmail(): void
 * -- testLoginFailsForIncorrectPassword(): void
 * -- testLoginFailsForAccountNotActivated(): void
 * -- testLoginFailsForAccountSuspended(): void
 * -- testLoginSucceedsForValidEmailAndPasswordAndActivated(): void
 *
 * checkPassword(): bool
 * -- testCheckPassword(): void
 *
 * getRandomPassword(): string
 * -- testGetRandomPasswordSuccess(): void
 *
 * checkUserExist(): bool
 * -- testCheckUserExistFalse(): void
 * -- testCheckUserExistTrue(): void
 *
 * checkEmailExist(): bool
 * -- testCheckEmailExistFalse(): void
 * -- testCheckEmailExistTrue(): void
 */
declare ( strict_types = 1 );

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
class UserTest extends PicnicTestCase {
	const USER_ID = 'userID';
	const USER = 'user';
	const EMAIL = 'email';
	const PASSWORD = 'password';
	const STATUS = 'status';
	const ACTIVATE = 'activate';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	const MODULE = 'picnic';
	const USER_ID_1 = 1;
	const USER_ONE = 'peter';
	const EMAIL_ADDRESS_ONE = 'peter@gmail.com';
	const PASSWORD_ONE = 'TestTest88';
	const PASSWORD_ONE_ENCRYPTED = '613b59f86203d9e986e08514d175a7d690f3b8f9';
	const USER_ID_2 = 2;
	const USER_TWO = 'mary';
	const EMAIL_ADDRESS_TWO = 'mary@gmail.com';
	const PASSWORD_TWO = 'TestTest77';
	const USER_ID_3 = 3;
	const USER_THREE = 'adrian';
	const EMAIL_ADDRESS_THREE = 'adrian@gmail.com';
	const PASSWORD_THREE = 'TestTest66';
	const ACTIVATE_THREE = 'bcd1389292792cbee34cde3c80da74d8';
	const USER_ADD = 'brian';
	const EMAIL_ADD = 'brian@gmail.com';
	const PASSWORD_ADD = 'TestTest22';
	const USER_SHORT = 'bri';
	const USER_NOT_ALPHA = 'bri88an';
	const EMAIL_BAD = 'brian.gmail.com';
	const PASSWORD_SHORT = 'Test88';
	const PASSWORD_NO_UPPER = 'testtest88';
	const PASSWORD_NO_LOWER = 'TESTTEST88';
	const PASSWORD_NO_NUMBER = 'TestTestTest';
	const FAILED_TO_ADD_USER = 'Failed to add User.';
	const ERROR_USER_ID_EMPTY = 'Input is required!';
	const ERROR_USER_ID_INVALID = 'Invalid userID!';
	const ERROR_USER_ID_NOT_INT = 'UserID must be an integer!';
	const ERROR_USER_NOT_EXIST = 'User does not exist!';
	const ERROR_USER_EMPTY = 'Username Error: Input is required!';
	const ERROR_USER_SHORT = 'Input must be at least 4 characters in length!';
	const ERROR_USER_NOT_ALPHA = 'Input must consist of letters only!';
	const ERROR_USER_DUPLICATE = 'This user name is not available!';
	const ERROR_USER_SET = 'Failed to create user!';
	const ERROR_USER_NOT_UPDATED = 'User Not Updated!';
	const ERROR_EMAIL_EMPTY = 'Input is required!';
	const ERROR_EMAIL_INVALID = 'Email Error: Email address must be valid!';
	const ERROR_EMAIL_DUPLICATE = 'This email address is not available!';
	const ERROR_EMAIL_NOT_EXIST = 'Email does not exist!';
	const ERROR_PASSWORD_EMPTY = 'Password Error: Input is required!';
	const ERROR_PASSWORD_INVALID = 'Password Error: At least one uppercase letter, one lowercase letter, one digit and a minimum of eight characters!';
	const ERROR_PASSWORD_NOT_UPDATED = 'Password Not Updated!';
	const ERROR_ACTIVATION_CODE_INVALID = 'Failed to activate account!';
	const ERROR_ACTIVATION_CODE_SHORT = 'Activation code must be 32 characters in length!';
	const ERROR_ACTIVATION_CODE_EMPTY = 'Input is required!';
	const ERROR_ACTIVATION_CODE_RETRIEVE = 'Failed to retrieve user!';
	const ERROR_ACTIVATION_CODE_NOT_MATCH = 'Activation Codes did not match!';
	const ERROR_ACTIVATION_FAILURE = 'Failed to activate account!';
	const INVALID_ID = 200;
	const INVALID_ACTIVATION_CODE = 'ef4flslerlwldxl234lsdl3wdxl234ls';
	const SHORT_ACTIVATION_CODE = 'ef4flslerlwldxl234lsdl3wdxl234l';
	const ERROR_ACTIVATION_CODE = 'Failed to retrieve UserID!';
	const WRONG_USER = 'john';
	const WRONG_EMAIL_ADDRESS = 'john@hotmail.com';
	const WRONG_PASSWORD = 'TestTest99';
	const STATUS_ADD = 'suspended';
	const STATUS_ACTIVE = 'active';
	const STATUS_SUSPENDED = 'suspended';
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		$args1 = [
				self::USER => self::USER_ONE,
				self::EMAIL => self::EMAIL_ADDRESS_ONE,
				self::PASSWORD => self::PASSWORD_ONE
		];

		$args2 = [
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::PASSWORD => self::PASSWORD_TWO
		];

		$args3 = [
				self::USER => self::USER_THREE,
				self::EMAIL => self::EMAIL_ADDRESS_THREE,
				self::PASSWORD => self::PASSWORD_THREE
		];

		// Populate the Users table.
		
		for($i = 1; $i < 4; $i ++) {
			${'u' . $i} = new User ( $pdo, ${'args' . $i} );
			try {
				${'u' . $i}->set ();
			} catch ( ModelException $e ) {
				$this->assertEquals('Exception', $e->getMessage());
			}
			try {
				${'u' . $i}->get ();
			} catch ( ModelException $e ) {
				$this->assertEquals('Exception', $e->getMessage());
			}
			if ($i != 3) {
				try {
					${'u' . $i}->activate ();
				} catch ( ModelException $e ) {
					$this->assertEquals('Exception', $e->getMessage());
				}
			}
		}
		$_SESSION = array ();
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new User ( TestPDO::getInstance () );
	}
	protected function createSutWithId(int $id) {
		return new User ( TestPDO::getInstance (), [ 
				self::USER_ID => $id 
		] );
	}
	protected function getValidId() {
		return 1;
	}
	protected function getInvalidId() {
		return 200;
	}
	protected function getExpectedExceptionTypeForUnknownId() {
		return ModelException::class;
	}
	protected function getExpectedExceptionTypeForUnsetId() {
		return ModelException::class;
	}
	protected function getExpectedAttributesForGet() {
		return [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ONE,
				self::EMAIL => self::EMAIL_ADDRESS_ONE,
				self::PASSWORD => self::PASSWORD_ONE_ENCRYPTED,
				self::STATUS => self::STATUS_ACTIVE,
				self::ACTIVATE => null 
		];
	}
	public function testAttributes(): void {
		$values = [ 
				self::USER_ID => self::USER_ID_2,
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::PASSWORD => self::PASSWORD_TWO,
				self::STATUS => self::STATUS_ACTIVE,
				self::ACTIVATE => self::ACTIVATE_THREE,
				self::CREATION_DATE => '1984-08-18',
				self::MODIFIED_DATE => '2015-02-13' 
		];
		
		$this->assertAttributesAreSetAndRetrievedCorrectly ( $values );
	}
	
	/*
	 * get(): User
	 */
	public function testGetUserNoUserId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_EMPTY );
		$sut->get ();
	}
	public function testGetUserInvalidUserId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_USER_NOT_EXIST );
		$sut->get ();
	}
	public function testGetUserValidId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		try {
			$sut->get ();
			$this->assertSame ( self::USER_ID_1, $sut->userID );
			$this->assertSame ( self::USER_ONE, $sut->user );
			$this->assertSame ( self::EMAIL_ADDRESS_ONE, $sut->email );
			$this->assertEquals ( 40, strlen ( $sut->password ) );
			$this->assertSame ( self::STATUS_ACTIVE, $sut->status );
			$this->assertNull ( $sut->activate );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * getUserIdByEmail(): int
	 */
	public function testGetUserIdByEmailNoEmail(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = NULL;
		$this->expectExceptionMessage ( self::ERROR_EMAIL_EMPTY );
		$sut->getUserIdByEmail ();
	}
	public function testGetUserIdByEmailBadEmail(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::EMAIL_BAD;
		$this->expectExceptionMessage ( self::ERROR_EMAIL_INVALID );
		$sut->getUserIdByEmail ();
	}
	public function testGetUserIdByEmailNotExistEmail(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		$this->expectExceptionMessage ( self::ERROR_EMAIL_NOT_EXIST );
		$sut->getUserIdByEmail ();
	}
	public function testGetUserIdByEmailExistEmail(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::EMAIL_ADDRESS_TWO;
		$sut->userID = $sut->getUserIdByEmail ();
		$this->assertEquals ( self::USER_ID_2, $sut->userID );
	}
	
	/*
	 * set(): int
	 */
	public function testSetNoUser(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => NULL,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_USER_EMPTY );
		$sut->set ();
	}
	public function testSetShortUser(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_SHORT,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_USER_SHORT );
		$sut->set ();
	}
	public function testSetExistUser(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_USER_DUPLICATE );
		$sut->set ();
	}
	public function testSetNoEmail(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => NULL,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_EMAIL_EMPTY );
		$sut->set ();
	}
	public function testSetBadEmail(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_BAD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_EMAIL_INVALID );
		$sut->set ();
	}
	public function testSetExistEmail(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_EMAIL_DUPLICATE );
		$sut->set ();
	}
	public function testSetNoPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => NULL 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_EMPTY );
		$sut->set ();
	}
	public function testSetShortPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_SHORT 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_INVALID );
		$sut->set ();
	}
	public function testSetNoUpperPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_UPPER 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_INVALID );
		$sut->set ();
	}
	public function testSetNoLowerPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_LOWER 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_INVALID );
		$sut->set ();
	}
	public function testSetNoNumberPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_NO_NUMBER 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_INVALID );
		$sut->set ();
	}
	public function testSetSuccessful(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::PASSWORD => self::PASSWORD_ADD 
		] );
		try {
			$this->assertEquals ( 4, $sut->set () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateUserNoUserID(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_USER_ID_EMPTY );
		$sut->update ();
	}
	public function testUpdateUserNoUser(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => NULL,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_USER_EMPTY );
		$sut->update ();
	}
	public function testUpdateUserShortUser(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_SHORT,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_USER_SHORT );
		$sut->update ();
	}
	public function testUpdateUserExistUser(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_TWO,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_USER_DUPLICATE );
		$sut->update ();
	}
	public function testUpdateUserNoEmail(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => NULL,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_EMAIL_EMPTY );
		$sut->update ();
	}
	public function testUpdateUserBadEmail(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_BAD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_EMAIL_INVALID );
		$sut->update ();
	}
	public function testUpdateUserExistEmail(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADDRESS_TWO,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->expectExceptionMessage ( self::ERROR_EMAIL_DUPLICATE );
		$sut->update ();
	}
	public function testUpdateUserNoStatus(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( Exception $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertEquals ( self::STATUS_ADD, $sut->status );
	}
	public function testUpdateUserSuccessful(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::USER => self::USER_ADD,
				self::EMAIL => self::EMAIL_ADD,
				self::STATUS => self::STATUS_ADD 
		] );
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( Exception $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertEquals ( self::USER_ADD, $sut->user );
		$this->assertEquals ( self::EMAIL_ADD, $sut->email );
		$this->assertEquals ( self::STATUS_ADD, $sut->status );
	}
	
	/*
	 * updatePassword(): bool
	 */
	public function testUpdatePasswordNoPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => NULL 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_EMPTY );
		$sut->updatePassword ();
	}
	public function testUpdatePasswordShortPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_SHORT 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_INVALID );
		$sut->updatePassword ();
	}
	public function testUpdatePasswordNoUpperPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_NO_UPPER 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_INVALID );
		$sut->updatePassword ();
	}
	public function testUpdatePasswordNoLowerPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_NO_LOWER 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_INVALID );
		$sut->updatePassword ();
	}
	public function testUpdatePasswordNoNumberPassword(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_NO_NUMBER 
		] );
		$this->expectExceptionMessage ( self::ERROR_PASSWORD_INVALID );
		$sut->updatePassword ();
	}
	public function testUpdatePasswordSuccessful(): void {
		$pdo = TestPDO::getInstance ();
		$sut = new User ( $pdo, [ 
				self::USER_ID => 1,
				self::PASSWORD => self::PASSWORD_ADD 
		] );

		$sut->get ();
		$oldPassword = $sut->password;
		$sut->password = self::PASSWORD_ADD;
		$this->assertTrue ( $sut->updatePassword () );
		$sut->get ();
		$newPassword = $sut->get ()->password;

		$this->assertTrue (strlen ( $oldPassword ) > 0 && strlen ( $newPassword ) > 0 && $oldPassword != $newPassword);
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteNoUserID(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_EMPTY );
		$sut->delete ();
	}
	public function testDeleteInvalidUserID(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_USER_ID_INVALID );
		$sut->delete ();
	}
	public function testDeleteValidUserID(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		try {
			$sut->delete ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$sut = $this->createSutWithId ( $this->getValidId () );
		$this->expectExceptionMessage ( self::ERROR_USER_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * exists(): bool
	 */
	public function testExistsNoUserID(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_EMPTY );
		$sut->exists ();
	}
	public function testExistsInvalidUserID(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		try {
			$this->assertFalse ( $sut->exists () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	public function testExistsValidUserID(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		try {
			$this->assertTrue ( $sut->exists () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * countUser(): int
	 */
	public function testCountUserNoUser(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_EMPTY );
		$sut->countUser ();
	}
	public function testCountUserShortName(): void {
		$sut = $this->createDefaultSut ();
		$sut->user = self::USER_SHORT;
		$this->expectExceptionMessage ( self::ERROR_USER_SHORT );
		$sut->countUser ();
	}
	public function testCountUserNotAlphaName(): void {
		$sut = $this->createDefaultSut ();
		$sut->user = self::USER_NOT_ALPHA;
		$this->expectExceptionMessage ( self::ERROR_USER_NOT_ALPHA );
		$sut->countUser ();
	}
	public function testCountUserValidUser(): void {
		$sut = $this->createDefaultSut ();
		$sut->user = self::USER_ONE;
		try {
			$this->assertEquals ( 1, $sut->countUser () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * countEmail(): int
	 */
	public function testCountEmailNoEmail(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_EMAIL_EMPTY );
		$sut->countEmail ();
	}
	public function testCountEmailInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::EMAIL_BAD;
		$this->expectExceptionMessage ( self::ERROR_EMAIL_INVALID );
		$sut->countEmail ();
	}
	public function testCountEmailValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::EMAIL_ADDRESS_ONE;
		try {
			$this->assertEquals ( 1, $sut->countEmail () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * getUserIdByActivationCode() Test
	 */
	public function testGetUserIdByActivationCodeEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ACTIVATION_CODE_EMPTY );
		$sut->getUserIdByActivationCode ();
	}
	public function testGetUserIdByActivationCodeIncorrectLength(): void {
		$sut = $this->createDefaultSut ();
		$sut->activate = self::SHORT_ACTIVATION_CODE;
		$this->expectExceptionMessage ( self::ERROR_ACTIVATION_CODE_SHORT );
		$sut->getUserIdByActivationCode ();
	}
	public function testGetUserIdByActivationCodeInvalidCode(): void {
		$sut = $this->createDefaultSut ();
		$sut->activate = self::INVALID_ACTIVATION_CODE;
		$this->expectExceptionMessage ( self::ERROR_ACTIVATION_CODE_RETRIEVE );
		$sut->getUserIdByActivationCode ();
	}
	public function testGetUserIdByActivationCodeValidCode(): void {
		$expected = $this->createSutWithId ( 3 );
		try {
			$expected->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$sut = $this->createDefaultSut ();
		$sut->activate = $expected->activate;
		try {
			$sut->userID = $sut->getUserIdByActivationCode ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertEquals ( $expected->userID, $sut->userID );
	}
	
	/*
	 * activate(): bool
	 */
	public function testActivateNoUserID(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_EMPTY );
		$sut->activate ();
	}
	public function testActivateInvalidUserID(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_USER_NOT_EXIST );
		$sut->activate ();
	}
	public function testActivateValidUserID(): void {
		$sut = $this->createSutWithId ( 3 );
		try {
			$sut->activate ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertNull ( $sut->activate );
	}
	
	/*
	 * login(): bool
	 */
	public function testLogoutClearsSessionData(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::EMAIL_ADDRESS_ONE;
		$sut->password = self::PASSWORD_ONE;
		try {
			$sut->login ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		$this->assertNotEmpty ( $_SESSION );
		
		$sut->logout ();
		
		$this->assertEmpty ( $_SESSION );
	}
	public function testLoginFailureDoesNotCreateSessionData(): void {
		unset ( $_SESSION [self::MODULE] );
		
		$sut = $this->createDefaultSut ();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		$sut->password = self::WRONG_PASSWORD;
		
		try {
			$sut->login ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		$this->assertEmpty ( $_SESSION );
	}
	public function testLoginFailsForIncorrectEmail(): void {
		unset ( $_SESSION [self::MODULE] );
		
		$sut = $this->createDefaultSut ();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		$sut->password = self::PASSWORD_ONE;
		
		try {
			$bool = $sut->login ();
		} catch ( ModelException $e ) {
			$bool = false;
		}
		$this->assertFalse ( $bool );
	}
	public function testLoginFailsForIncorrectPassword(): void {
		unset ( $_SESSION [self::MODULE] );
		
		$sut = $this->createDefaultSut ();
		$sut->email = self::EMAIL_ADDRESS_ONE;
		$sut->password = self::WRONG_PASSWORD;
		
		try {
			$bool = $sut->login ();
		} catch ( ModelException $e ) {
			$bool = false;
		}
		$this->assertFalse ( $bool );
	}
	public function testLoginFailsForAccountNotActivated(): void {
		unset ( $_SESSION [self::MODULE] );
		
		$sut = $this->createSutWithId ( 3 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		try {
			$bool = $sut->login ();
		} catch ( ModelException $e ) {
			$bool = false;
		}
		$this->assertFalse ( $bool );
	}
	public function testLoginFailsForAccountSuspended(): void {
		$u = $this->createSutWithId ( 1 );
		$u->user = self::USER_ONE;
		$u->email = self::EMAIL_ADDRESS_ONE;
		$u->status = self::STATUS_SUSPENDED;
		try {
			$u->update ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		unset ( $_SESSION [self::MODULE] );
		
		$sut = $this->createSutWithId ( 1 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		try {
			$bool = $sut->login ();
		} catch ( ModelException $e ) {
			$bool = false;
		}
		$this->assertFalse ( $bool );
	}
	public function testLoginSucceedsForValidEmailAndPasswordAndActivated(): void {
		unset ( $_SESSION [self::MODULE] );
		
		$sut = $this->createSutWithId ( 1 );
		$sut->email = self::EMAIL_ADDRESS_ONE;
		$sut->password = self::PASSWORD_ONE;
		
		try {
			$bool = $sut->login ();
		} catch ( ModelException $e ) {
			$bool = false;
		}
		$this->assertTrue ( $bool );
	}
	public function testSuccessfulLoginCreatesSessionData(): void {
		$this->assertEmpty ( $_SESSION );
		
		$sut = $this->createSutWithId ( 1 );
		$sut->email = self::EMAIL_ADDRESS_ONE;
		$sut->password = self::PASSWORD_ONE;
		try {
			$sut->login ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		$this->assertNotEmpty ( $_SESSION );
	}
	
	/*
	 * checkPassword(): bool
	 */
	public function testCheckPassword(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::EMAIL_ADDRESS_TWO;
		$sut->password = self::PASSWORD_THREE;
		$this->assertFalse ( $sut->checkPassword () );
		
		$sut->password = self::PASSWORD_TWO;
		$this->assertTrue ( $sut->checkPassword () );
	}
	
	/*
	 * getRandomPassword(): string
	 */
	public function testGetRandomPasswordSuccess(): void {
		$sut = $this->createDefaultSut ();
		$this->assertEquals ( 10, strlen ( $sut->getRandomPassword () ) );
	}
	
	/*
	 * checkUserExist(): bool
	 */
	public function testCheckUserExistFalse(): void {
		$sut = $this->createDefaultSut ();
		$sut->user = self::WRONG_USER;
		try {
			$this->assertFalse ( $sut->checkUserExist () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	public function testCheckUserExistTrue(): void {
		$sut = $this->createDefaultSut ();
		$sut->user = self::USER_TWO;
		$this->expectExceptionMessage ( self::ERROR_USER_DUPLICATE );
		$sut->checkUserExist ();
	}
	
	/*
	 * checkEmailExist(): bool
	 */
	public function testCheckEmailExistFalse(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		try {
			$this->assertFalse ( $sut->checkEmailExist () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	public function testCheckEmailExistTrue(): void {
		$sut = $this->createDefaultSut ();
		$sut->email = self::EMAIL_ADDRESS_TWO;
		$this->expectExceptionMessage ( self::ERROR_EMAIL_DUPLICATE );
		$sut->checkEmailExist ();
	}
}
