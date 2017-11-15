<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */
declare ( strict_types = 1 );

require_once 'TestPDO.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/Comment.php';
require_once dirname ( __FILE__ ) . '/../../../model/UserComments.php';
require_once dirname ( __FILE__ ) . '/../../../model/Item.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
class UserCommentsTest extends PHPUnit\Framework\TestCase {
	const USER_ID = 'userID';
	const USER = 'user';
	const EMAIL = 'email';
	const PASSWORD = 'password';
	const STATUS = 'status';
	const ACTIVATE = 'activate';
	const USER_ONE = 'peter';
	const ITEM_ID = 'itemID';
	const OWNING_USER_ID = 'owningUserID';
	const COMMENT = 'comment';
	const TITLE = 'title';
	const DESCRIPTION = 'description';
	const QUANTITY = 'quantity';
	const CONDITION = 'itemcondition';
	const TYPE = 'type';
	const PRICE = 'price';
	const EMAIL_ADDRESS_ONE = 'peter@gmail.com';
	const PASSWORD_ONE = 'TestTest88';
	const USER_TWO = 'mary';
	const EMAIL_ADDRESS_TWO = 'mary@gmail.com';
	const PASSWORD_TWO = 'TestTest77';
	const USER_THREE = 'adrian';
	const EMAIL_ADDRESS_THREE = 'adrian@gmail.com';
	const PASSWORD_THREE = 'TestTest66';
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
			${'u' . $i}->activate ();
		}

		$item = new Item ( $pdo, [
			self::ITEM_ID => 1,
			self::OWNING_USER_ID => 1,
			self::TITLE => "blah",
			self::DESCRIPTION => "blah",
			self::QUANTITY => 1,
			self::CONDITION => "Used",
			self::TYPE => "Wanted",
			self::PRICE => 1,
			self::STATUS => "ForSale"
		]);

		$item->set();

		$k = 1;
		for($i = 1; $i < 4; $i ++) {
			for($j = 1; $j < 6; $j ++) {
				$c = new Comment ( $pdo );
				$c->toUserID = $i;
				$c->fromUserID = $i;
				$c->itemID = 1;
				$c->comment = 'Comment' . $k;
				$c->status = 'Status' . $k;
				try {
					$c->set ();
					$k ++;
				} catch ( ModelException $e ) {
					$this->assertEquals('Exception', $e->getMessage());
				}
			}
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new UserComments ( TestPDO::getInstance () );
	}
	protected function createSutWithId($id) {
		return new UserComments ( TestPDO::getInstance (), [ 
				self::USER_ID => $id 
		] );
	}
	protected function getValidId() {
		return 2;
	}
	protected function getInvalidId() {
		return 200;
	}
	public function testGetUserCommentsFailsInvalidId() {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		
		$this->assertEmpty ( $sut->getUserComments () );
	}
	public function testGetUserCommentsSuccessValidId() {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$objects = $sut->getUserComments ();
		
		$i = 6;
		foreach ( $objects as $object ) {
			$this->assertEquals ( $i, $object->commentID );
			$this->assertEquals ( 'Comment' . $i, $object->comment );
			$i ++;
		}
	}
}
