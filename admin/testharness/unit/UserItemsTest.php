<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */

/*
 * TEST SUMMARY
 *
 * -- UserItems.php Test Blocks: --
 * get(): UserItems
 * set(): int
 * update(): bool
 * delete(): bool
 * exists(): bool
 * existsItemID(): bool
 * count(): int
 * getUserItems(): array
 * getUserItem(): UserItems
 * deleteItem(): bool
 * deleteUser(): bool
 * count(): int
 * getUserItems(): array
 * getUserItem(): UserItems
 * deleteItem(): bool
 * deleteUser(): bool
 *
 * -- UserItems.php Test SubBlocks: --
 * get(): UserItems
 * -- testGetUserItemsNoUserItemsId(): void
 * -- testGetUserItemsInvalidUserItemsId(): void
 * -- testGetUserItemsValidUserItemsId():
 *
 * set(): int
 * -- testSetUserItemsEmpty(): void
 * -- testSetUserItemsInvalidUserId(): void
 * -- testSetUserItemsInvalidItemId(): void
 * -- testSetUserItemsExistingItemId(): void
 * -- testSetUserItemsSuccess(): void
 *
 * update(): bool
 * -- testUpdateUserItemsNoUserItemsId(): void
 * -- testUpdateUserItemsInvalidUserItemsId(): void
 * -- testUpdateUserItemsInvalidUserId(): void
 * -- testUpdateUserItemsInvalidItemId(): void
 * -- testUpdateUserItemsExistingItemId(): void
 * -- testUpdateUserItemsSuccess(): void
 *
 * delete(): bool
 * -- testDeleteUserItemsUserItemsIdEmpty(): void
 * -- testDeleteUserItemsUserItemsIdInvalid(): void
 * -- testDeleteUserItemsUserItemsIdValid(): void
 *
 * exists(): bool
 * -- testExistsUserItemsUserItemsIdEmpty(): void
 * -- testExistsUserItemsUserItemsIdInvalid(): void
 * -- testExistsUserItemsUserItemsIdValid(): void
 *
 * existsItemID(): bool
 * -- testExistsUserItemsItemIdEmpty(): void
 * -- testExistsUserItemsItemIdInvalid(): void
 * -- testExistsUserItemsItemIdValid(): void
 *
 * count(): int
 * -- testCountUserIdEmpty(): void
 * -- testCountUserIdInvalid(): void
 * -- testCountUserIdValid(): void
 *
 * getUserItems(): array
 * -- testGetUserItemsUserIdEmpty(): void
 * -- testGetUserItemsUserIdInvalid(): void
 * -- testGetUserItemsUserIdValid(): void
 *
 * getUserItem(): UserItems
 * -- testGetUserItemItemIdEmpty(): void
 * -- testGetUserItemItemIdInvalid(): void
 * -- testGetUserItemItemIdValid(): void
 *
 * deleteItem(): bool
 * -- testDeleteItemItemIdEmpty(): void
 * -- testDeleteItemItemIdInvalid(): void
 * -- testDeleteItemItemIdValid(): void
 *
 * deleteUser(): bool
 * -- testDeleteUserUserIdEmpty(): void
 * -- testDeleteUserUserIdInvalid(): void
 * -- testDeleteUserUserIdValid(): void
 */
declare ( strict_types = 1 );

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/UserItems.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/Item.php';
require_once dirname ( __FILE__ ) . '/../../../model/UserItems.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
final class UserItemsTest extends PicnicTestCase {
	const USER_ITEM_ID = 'user_itemID';
	const USER_ID = 'userID';
	const ITEM_ID = 'itemID';
	const RELATIONSHIP = 'relationship';
	const USERSTATUS = 'userStatus';
	const USER_ITEM_ID_1 = 1;
	const USER_ID_1 = 1;
	const ITEM_ID_1 = 1;
	const USER_ITEM_ID_2 = 2;
	const USER_ID_2 = 2;
	const ITEM_ID_2 = 2;
	const USER_ITEM_ID_10 = 10;
	const USER_ITEM_ID_15 = 15;
	const USER_ID_3 = 3;
	const ITEM_ID_5 = 5;
	const ITEM_ID_15 = 15;
	const RELATIONSHIP_1 = 'Relationship1';
	const RELATIONSHIP_2 = 'Relationship2';
	const USERSTATUS_1 = 'UserStatus1';
	const USERSTATUS_2 = 'UserStatus2';
	const ERROR_USER_ITEM_ID_NOT_EXIST = 'The UserItemID does not exist!';
	const ERROR_USER_ID_NOT_EXIST = 'The UserID does not exist!';
	const ERROR_USER_ID_NOT_INT = 'UserID must be an integer!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_ITEM_ID_ALREADY_EXIST = 'The ItemID is already in User_items!';
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		$l = 1;
		for($i = 1; $i <= 3; $i ++) {
			$user = new User ( $pdo );
			$user->user = 'user' . $i;
			$user->email = 'user' . $i . '@gmail.com';
			$user->password = 'PassWord' . $i . $i;
			try {
				$user->set ();
			} catch ( ModelException $e ) {
			}
			
			for($j = 1; $j <= 5; $j ++) {
				$item = new Item ( $pdo );
				$item->owningUserID = $user->userID;
				$item->title = 'title' . $l;
				try {
					$item->set ();
					$userItem = new UserItems ( $pdo );
					$userItem->userID = $i;
					$userItem->itemID = $l;
					$userItem->relationship = 'relationship' . $i . $l;
					$userItem->userStatus = 'userStatus' . $i . $l;
					
					try {
						if ($userItem->userID == 3 && $userItem->itemID == 15) {
							// Don't set.
						} else {
							$userItem->set ();
						}
					} catch ( ModelException $e ) {
					}
				} catch ( Exception $e ) {
				}
				$l ++;
			}
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new UserItems ( TestPDO::getInstance () );
	}
	protected function createSutWithId($id) {
		return new UserItems ( TestPDO::getInstance (), [ 
				self::USER_ITEM_ID => $id 
		] );
	}
	protected function getValidId() {
		return 1;
	}
	protected function getInvalidId() {
		return 2000;
	}
	protected function getExpectedAttributesForGet() {
		return [ 
				self::USER_ITEM_ID => self::ITEM_ID_1,
				self::USER_ID => self::USER_ID_1,
				self::ITEM_ID => self::ITEM_ID_1,
				self::RELATIONSHIP => self::RELATIONSHIP_1,
				self::USERSTATUS => self::USERSTATUS_1 
		];
	}
	public function testAttributes(): void {
		$values = [ 
				self::USER_ITEM_ID => self::ITEM_ID_2,
				self::USER_ID => self::USER_ID_2,
				self::ITEM_ID => self::ITEM_ID_2,
				self::RELATIONSHIP => self::RELATIONSHIP_2,
				self::USERSTATUS => self::USERSTATUS_2 
		];
		
		$this->assertAttributesAreSetAndRetrievedCorrectly ( $values );
	}
	
	/*
	 * get(): UserItems
	 */
	public function testGetUserItemsNoUserItemsId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ITEM_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetUserItemsInvalidUserItemsId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_USER_ITEM_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetUserItemsValidUserItemsId(): void {
		$sut = $this->createSutWithId ( self::USER_ITEM_ID_2 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
		}
		$this->assertEquals ( self::USER_ITEM_ID_2, $sut->user_itemID );
		$this->assertEquals ( self::USER_ID_1, $sut->userID );
		$this->assertEquals ( self::ITEM_ID_2, $sut->itemID );
	}
	
	/*
	 * set(): int
	 */
	public function testSetUserItemsEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( '' );
		$sut->set ();
	}
	public function testSetUserItemsInvalidUserId(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = $this->getInvalidId ();
		$sut->itemID = self::ITEM_ID_5;
		$this->expectExceptionMessage ( self::ERROR_USER_ID_NOT_EXIST );
		$sut->set ();
	}
	public function testSetUserItemsInvalidItemId(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = self::USER_ID_3;
		$sut->itemID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->set ();
	}
	public function testSetUserItemsExistingItemId(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = self::USER_ID_3;
		$sut->itemID = self::ITEM_ID_2;
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_ALREADY_EXIST );
		$sut->set ();
	}
	public function testSetUserItemsSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = self::USER_ID_3;
		$sut->itemID = self::ITEM_ID_15;
		try {
			$sut->user_itemID = $sut->set ();
		} catch ( ModelException $e ) {
		}
		$sut = $this->createSutWithId ( $sut->user_itemID );
		try {
			$sut->get ();
			$this->assertEquals ( self::USER_ITEM_ID_15, $sut->user_itemID );
			$this->assertEquals ( self::USER_ID_3, $sut->userID );
			$this->assertEquals ( self::ITEM_ID_15, $sut->itemID );
		} catch ( ModelException $e ) {
		}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateUserItemsNoUserItemsId(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->update () );
	}
	public function testUpdateUserItemsInvalidUserItemsId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->update () );
	}
	public function testUpdateUserItemsInvalidUserId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->userID = $this->getInvalidId ();
		$sut->itemID = self::ITEM_ID_15;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
		}
		$this->assertSame ( '1', $sut->userID );
		$this->assertSame ( '15', $sut->itemID );
	}
	public function testUpdateUserItemsInvalidItemId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->userID = self::USER_ID_2;
		$sut->itemID = $this->getInvalidId ();
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
		}
		$this->assertSame ( '2', $sut->userID );
		$this->assertSame ( '1', $sut->itemID );
	}
	public function testUpdateUserItemsExistingItemId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->userID = self::USER_ID_2;
		$sut->itemID = self::ITEM_ID_2;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
		}
		$this->assertSame ( '2', $sut->userID );
		$this->assertSame ( '1', $sut->itemID );
	}
	public function testUpdateUserItemsSuccess(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->userID = self::USER_ID_2;
		$sut->itemID = self::ITEM_ID_15;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
		}
		$this->assertSame ( '2', $sut->userID );
		$this->assertSame ( '15', $sut->itemID );
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteUserItemsUserItemsIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ITEM_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteUserItemsUserItemsIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_USER_ITEM_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteUserItemsUserItemsIdValid(): void {
		$sut = $this->createSutWithId ( self::USER_ITEM_ID_2 );
		try {
			$this->assertTrue ( $sut->delete () );
		} catch ( ModelException $e ) {
		}
		$this->expectExceptionMessage ( self::ERROR_USER_ITEM_ID_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * exists(): bool
	 */
	public function testExistsUserItemsUserItemsIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsUserItemsUserItemsIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsUserItemsUserItemsIdValid(): void {
		$sut = $this->createSutWithId ( self::ITEM_ID_2 );
		$this->assertTrue ( $sut->exists () );
	}
	
	/*
	 * existsItemID(): bool
	 */
	public function testExistsItemIDItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->existsItemID () );
	}
	public function testExistsItemIDItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->assertFalse ( $sut->existsItemID () );
	}
	public function testExistsItemIDItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getValidId ();
		$this->assertTrue ( $sut->existsItemID () );
	}
	
	/*
	 * existsUserID(): bool
	 */
	public function testExistsUserIDUserIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_NOT_INT );
		$sut->existsUserID ();
	}
	public function testExistsUserIDUserIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = $this->getInvalidId ();
		$this->assertFalse ( $sut->existsUserID () );
	}
	public function testExistsUserIDUserIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = $this->getValidId ();
		$this->assertTrue ( $sut->existsUserID () );
	}
	
	/*
	 * count(): int
	 */
	public function testCountUserIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertEquals ( 0, $sut->count () );
	}
	public function testCountUserIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = $this->getInvalidId ();
		$this->assertEquals ( 0, $sut->count () );
	}
	public function testCountUserIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = $this->getValidId ();
		$this->assertEquals ( 5, $sut->count () );
	}
	
	/*
	 * getUserItems(): array
	 */
	public function testGetUserItemsUserIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_NOT_INT );
		$sut->getUserItems ();
	}
	public function testGetUserItemsUserIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_NOT_EXIST );
		$sut->getUserItems ();
	}
	public function testGetUserItemsUserIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = self::USER_ID_2;
		try {
			$i = 6;
			foreach ( $sut->getUserItems () as $obj ) {
				$this->assertEquals ( $i, $obj->user_itemID );
				$this->assertEquals ( 2, $obj->userID );
				$this->assertEquals ( $i, $obj->itemID );
				$i ++;
			}
		} catch ( ModelException $e ) {
		}
	}
	
	/*
	 * getUserItem(): UserItems
	 */
	public function testGetUserItemItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->getUserItem ();
	}
	public function testGetUserItemItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->getUserItem ();
	}
	public function testGetUserItemItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_2;
		try {
			$obj = $sut->getUserItem ();
			$this->assertEquals ( 2, $obj->user_itemID );
			$this->assertEquals ( 1, $obj->userID );
			$this->assertEquals ( 2, $obj->itemID );
		} catch ( ModelException $e ) {
		}
	}
	
	/*
	 * deleteUserItem(): bool
	 */
	public function testDeleteUserItemItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->deleteUserItem ();
	}
	public function testDeleteUserItemItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->deleteUserItem ();
	}
	public function testDeleteUserItemItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_2;
		try {
			$this->assertTrue ( $sut->deleteUserItem () );
		} catch ( ModelException $e ) {
		}
		$sut = $this->createSutWithId ( self::USER_ITEM_ID_2 );
		$this->expectExceptionMessage ( self::ERROR_USER_ITEM_ID_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * deleteUserItems(): bool
	 */
	public function testDeleteUserItemsUserIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_NOT_INT );
		$sut->deleteUserItems ();
	}
	public function testDeleteUserItemsUserIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_USER_ID_NOT_EXIST );
		$sut->deleteUserItems ();
	}
	public function testDeleteUserItemsUserIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = self::USER_ID_2;
		try {
			$this->assertTrue ( $sut->deleteUserItems () );
		} catch ( ModelException $e ) {
		}
		for($i = 6; $i <= 10; $i ++) {
			$sut = $this->createSutWithId ( $i );
			$this->expectExceptionMessage ( self::ERROR_USER_ITEM_ID_NOT_EXIST );
			$sut->get ();
		}
	}
} 
