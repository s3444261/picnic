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

	const CORRECT_EMAIL_ADDRESS = 'CorrectEmailAddress';
	const CORRECT_PASSWORD = 'CorrectPassword';
	const WRONG_EMAIL_ADDRESS = 'WrongEmailAddress';
	const WRONG_PASSWORD = 'WrongPassword';

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);

		$args = [
			self::USER 			=> 'grant',
			self::EMAIL 		=> self::CORRECT_EMAIL_ADDRESS,
			self::PASSWORD		=> self::CORRECT_PASSWORD,
			self::STATUS 		=> 'active',
			self::ACTIVATE 		=> 'blahblahblah',
			self::CREATION_DATE => '1984-08-18',
			self::MODIFIED_DATE => '2015-02-13'
		];

		$item = new User($pdo, $args);
		$item->set();
		$item->get();
		$item->activate();

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

	protected function getExpectedExceptionTypeForUnsetId() {
		return UserException::class;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::USER_ID 		=> 1,
			self::USER 			=> 'grant',
			self::EMAIL 		=> 'CorrectEmailAddress',
			self::PASSWORD		=> '65820e68486cb78216d87f65fd4816e96fb3f436',
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
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{self::USER_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		$sut = new User(TestPDO::getInstance(), [self::USER_ID => 1, self::USER => 'someUser', self::EMAIL => 'test@address.net', self::PASSWORD => 'TestTest88']);
		$this->assertEquals(2, $sut->set());
		$this->assertEquals(2, $sut->{self::USER_ID});
	}

	public function testUpdateUserNameSucceeds(): void
	{
		$NEW_USER_NAME = 'NewUserName';

		$updating = $this->createSutWithId(1);
		$updating->get();
		$updating->user = $NEW_USER_NAME;
		$updating->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals($NEW_USER_NAME, $sut->user);
	}

	public function testUpdateEmailAddressSucceeds(): void
	{
		$NEW_EMAIL_ADDRESS = 'NewEmailAddress';

		$updating = $this->createSutWithId(1);
		$updating->get();
		$updating->email = $NEW_EMAIL_ADDRESS;
		$updating->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals($NEW_EMAIL_ADDRESS, $sut->email);
	}

	public function testUpdatePasswordSucceeds(): void
	{
		$updating = $this->createSutWithId(1);
		$updating->get();
		$PREVIOUS_PASSWORD = $updating->password;

		$updating->password = 'NewPassword';
		$updating->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertNotEquals($PREVIOUS_PASSWORD, $sut->password);
	}

	public function testUpdateStatusSucceeds(): void
	{
		$NEW_STATUS = 'NewStatus';

		$updating = $this->createSutWithId(1);
		$updating->get();
		$updating->status = $NEW_STATUS;
		$updating->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals($NEW_STATUS, $sut->status);
	}

	public function testLogoutClearsSessionData() : void {

		$sut = $this->createDefaultSut();
		$sut->email = self::CORRECT_EMAIL_ADDRESS;
		$sut->password = self::CORRECT_PASSWORD;
		$sut->login();

		$this->assertNotEmpty($_SESSION);

		$sut->logout();

		$this->assertEmpty($_SESSION);
	}

	public function testLoginFailureDoesNotCreateSessionData(): void {
		$sut = $this->createDefaultSut();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		$sut->password = self::WRONG_PASSWORD;

		$sut->login();

		$this->assertEmpty($_SESSION);
	}

	public function testLoginFailsForIncorrectEmail(): void {
		$sut = $this->createDefaultSut();
		$sut->email = self::WRONG_EMAIL_ADDRESS;
		$sut->password = self::CORRECT_PASSWORD;

		$this->assertFalse($sut->login());
	}

	public function testLoginFailsForIncorrectPassword(): void {
		unset($_SESSION[self::MODULE]);

		$sut = $this->createDefaultSut();
		$sut->email = self::CORRECT_EMAIL_ADDRESS;
		$sut->password = self::WRONG_PASSWORD;

		$this->assertFalse($sut->login());
	}

	public function testLoginFailsForAccountNotActivated(): void {
		$args = [
			self::USER 			=> 'neo',
			self::EMAIL 		=> 'neo@neo.net',
			self::PASSWORD		=> 'neo',
			self::STATUS 		=> 'active',
			self::ACTIVATE 		=> 'fdsffdsfds',
			self::CREATION_DATE => '1984-08-18',
			self::MODIFIED_DATE => '2015-02-13'
		];

		$item = new User(TestPDO::getInstance(), $args);
		$item->set();

		$sut = $this->createDefaultSut();
		$sut->email = 'neo@neo.net';
		$sut->password = 'neo';

		$this->assertFalse($sut->login());
	}

	public function testLoginFailsForAccountSuspended(): void {
		$suspendedUser = $this->createSutWithId(1);
		$suspendedUser->get();
		$suspendedUser->password = self::CORRECT_PASSWORD;
		$suspendedUser->status = 'suspended';
		$suspendedUser->update();

		$sut = $this->createDefaultSut();
		$sut->email = self::CORRECT_EMAIL_ADDRESS;
		$sut->password = self::CORRECT_PASSWORD;

		$this->assertFalse($sut->login());
	}

	public function testLoginSucceedsForValidEmailAndPasswordAndActivated(): void {
		$sut = $this->createDefaultSut();
		$sut->email = self::CORRECT_EMAIL_ADDRESS;
		$sut->password = self::CORRECT_PASSWORD;

		$this->assertTrue($sut->login());
	}

	public function testSuccessfulLoginCreatesSessionData(): void {
		$this->assertEmpty($_SESSION);

		$sut = $this->createDefaultSut();
		$sut->email = self::CORRECT_EMAIL_ADDRESS;
		$sut->password = self::CORRECT_PASSWORD;
		$sut->login();

		$this->assertNotEmpty($_SESSION);
	}
}
