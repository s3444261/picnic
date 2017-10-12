<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

/*
 * TEST SUMMARY
 *
 * -- Users.php Test Blocks: --
 * getUsers(int $pageNumber, int $usersPerPage): array
 *
 * -- User.php Test SubBlocks: --
 * getUsers(int $pageNumber, int $usersPerPage): array
 * -- testGetUsersPageNumberZero(): void
 * -- testGetUsersUsersPerPageZero(): void
 * -- testGetUsersSuccess(): void
 * 
 */
declare(strict_types=1);

require_once 'TestPDO.php';
require_once dirname(__FILE__) . '/../../createDB/DatabaseGenerator.php';
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/Users.php';
require_once dirname(__FILE__) . '/../../../model/UserException.php';
require_once dirname(__FILE__) . '/../../../model/UsersException.php';
require_once dirname(__FILE__) . '/../../../model/Validation.php';
require_once dirname(__FILE__) . '/../../../model/ValidationException.php';

class UsersTest extends PHPUnit\Framework\TestCase {
	
	const USER_ID       = 'userID';
	const USER 			= 'user';
	const EMAIL         = 'email';
	const PASSWORD 		= 'password';
	const STATUS 		= 'status';
	const ACTIVATE 		= 'activate';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	const MODULE 		= 'picnic';
	
	const PAGE_NUMBER = 10;
	const USERS_PER_PAGE = 20;
	const PAGE_NUMBER_ZERO = 0;
	const USERS_PER_PAGE_ZERO = 0;
	const ERROR_ZERO = 'Number must be greater than zero!';
	

	
	protected function setUp(): void {
		
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);
		
		// Populate the Users table.
		
		for($i = 1; $i <= 400; $i++){
			${'u' . $i} = new User($pdo, [self::USER => 'user' . $i, self::EMAIL => 'email' . $i . '@gmail.com', self::PASSWORD => 'PassWord' . $i]);
			try { 
				${'u' . $i}->set();
			} catch (UserException $e) {}
			try {
				${'u' . $i}->get();
			} catch (UserException $e) { }
			try {
				${'u' . $i}->activate();
			} catch (UserException $e) {}
		} 
	}

	protected function tearDown(): void {
		TestPDO::CleanUp();
	}

	protected function createDefaultSut() {
		return new Users(TestPDO::getInstance());
	}

	/*
	 * getUsers(int $pageNumber, int $usersPerPage): array
	 */
	public function testGetUsersPageNumberZero(): void {
		$sut = $this->createDefaultSut();
		$this->expectExceptionMessage(self::ERROR_ZERO);
		$sut->getUsers(self::PAGE_NUMBER_ZERO, self::USERS_PER_PAGE);
	}
	
	public function testGetUsersUsersPerPageZero(): void {
		$sut = $this->createDefaultSut();
		$this->expectExceptionMessage(self::ERROR_ZERO);
		$sut->getUsers(self::PAGE_NUMBER, self::USERS_PER_PAGE_ZERO);
	}
	
	public function testGetUsersSuccess(): void {
		 $sut = $this->createDefaultSut();
		 try {
		 	$users = $sut->getUsers(self::PAGE_NUMBER, self::USERS_PER_PAGE);
		 	
		 	$pn = self::PAGE_NUMBER;
		 	$upp = self::USERS_PER_PAGE;
		 	
		 	$start = ($pn - 1)*$upp + 1; 
		 	
		 	foreach($users as $user){
		 		$this->assertEquals($start, $user->userID);
		 		$this->assertEquals('user' . $start, $user->user);
		 		$this->assertEquals('email' . $start . '@gmail.com', $user->email);
		 		$start++;
		 	}
		 	
		 } catch (UsersException $e) {}	 
	}
}
