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

if (session_status () == PHP_SESSION_NONE) {
	session_start ();
}

require_once dirname(__FILE__) . '/../../testharness/unit/TestPDO.php';
require_once dirname(__FILE__) . '/../../createDB/DatabaseGenerator.php';
require_once dirname(__FILE__) . '/../../../model/System.php';
require_once dirname(__FILE__) . '/../../../model/Category.php';
require_once dirname(__FILE__) . '/../../../model/CategoryItems.php';
require_once dirname(__FILE__) . '/../../../model/Comment.php';
require_once dirname(__FILE__) . '/../../../model/Comments.php';
require_once dirname(__FILE__) . '/../../../model/Item.php';
require_once dirname(__FILE__) . '/../../../model/ItemComments.php';
require_once dirname(__FILE__) . '/../../../model/ItemNotes.php';
require_once dirname(__FILE__) . '/../../../model/Note.php';
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/UserItems.php';
require_once dirname(__FILE__) . '/../../../model/UserRatings.php';
require_once dirname(__FILE__) . '/../../../model/Users.php';
require_once dirname(__FILE__) . '/../../../model/Validation.php';
require_once dirname(__FILE__) . '/../../../model/CategoryException.php';
require_once dirname(__FILE__) . '/../../../model/CommentException.php';
require_once dirname(__FILE__) . '/../../../model/ItemException.php';
require_once dirname(__FILE__) . '/../../../model/ItemCommentsException.php';
require_once dirname(__FILE__) . '/../../../model/ItemNotesException.php';
require_once dirname(__FILE__) . '/../../../model/NoteException.php';
require_once dirname(__FILE__) . '/../../../model/UserException.php';
require_once dirname(__FILE__) . '/../../../model/UserRatingsException.php';
require_once dirname(__FILE__) . '/../../../model/ValidationException.php';

class HumphreeTest extends PHPUnit\Framework\TestCase {
	
	// User Parameters
	const USER_ID       = 'userID';
	const USER 			= 'user';
	const EMAIL         = 'email';
	const PASSWORD 		= 'password';
	const STATUS 		= 'status';
	const ACTIVATE 		= 'activate';
	
	// User Data
	const USER_ONE = 'peter';
	const EMAIL_ADDRESS_ONE = 'peter@gmail.com';
	const PASSWORD_ONE = 'TestTest88';
	const USER_TWO = 'mary';
	const EMAIL_ADDRESS_TWO = 'mary@gmail.com';
	const PASSWORD_TWO = 'TestTest77';
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
	
	// Category Parameters
	const CATEGORY_ID   = 'categoryID';
	const PARENT_ID	 	= 'parentID';
	const CATEGORY      = 'category';
	
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
		/*
		for($i = 1; $i < 4; $i++){
			${'u' . $i} = new User($pdo, ${'args' . $i});
			try {
				${'u' . $i}->set();
			} catch (UserException $e) { }
			try {
				${'u' . $i}->get();
			} catch (UserException $e) { }
			${'u' . $i}->activate();
		}	*/	
	}
	
	/*
	 * AddUser() Tests
	 */
	
	public function testAddUserNoUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => NULL, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserShortUser(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_SHORT, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => NULL, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserBadEmail(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_BAD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => NULL]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserShortPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_SHORT]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoUpperPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_UPPER]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoLowerPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_LOWER]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserNoNumberPassword(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_NO_NUMBER]);
		$this->assertFalse($system->addUser($sut));
		if(isset($_SESSION['error'])){
			$this->assertEquals(self::FAILED_TO_ADD_USER, $_SESSION['error']);
		}
	}
	
	public function testAddUserSuccessful(): void{
		unset($_SESSION);
		$pdo = TestPDO::getInstance();
		$system = new System ( $pdo );
		$sut = new User($pdo, [self::USER => self::USER_ADD, self::EMAIL => self::EMAIL_ADD, self::PASSWORD => self::PASSWORD_ADD]);
		$this->assertTrue($system->addUser($sut));
	}
	
}
