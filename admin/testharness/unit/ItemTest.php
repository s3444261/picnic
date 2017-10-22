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
 * -- Item.php Test Blocks: --
 * get(): Item
 * set(): int
 * update(): bool
 * delete(): bool
 * exists(): bool
 *
 * -- Item.php Test SubBlocks: --
 * get(): Item
 * -- testGetItemNoItemId(): void
 * -- testGetItemInvalidItemId(): void
 * -- testGetItemValidItemId():
 *
 * set(): int
 * -- testSetItemEmptyItem(): void
 * -- testSetItemSuccess(): void
 *
 * update(): bool
 * -- testUpdateItemNoItemId(): void
 * -- testUpdateItemInvalidItemId(): void
 * -- testUpdateItemEmptyItem(): void
 * -- testUpdateItemSuccess(): void
 *
 * delete(): bool
 * -- testDeleteItemItemIdEmpty(): void
 * -- testDeleteItemItemIdInvalid(): void
 * -- testDeleteItemItemIdValid(): void
 *
 * exists(): bool
 * -- testExistsItemItemIdEmpty(): void
 * -- testExistsItemItemIdInvalid(): void
 * -- testExistsItemItemIdValid(): void
 */
declare ( strict_types = 1 )
	;

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/Item.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
class ItemTest extends PicnicTestCase {
	const ITEM_ID = 'itemID';
	const TITLE = 'title';
	const DESCRIPTION = 'description';
	const QUANTITY = 'quantity';
	const CONDITION = 'itemcondition';
	const PRICE = 'price';
	const STATUS = 'status';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	const ITEM_ID_1 = 1;
	const TITLE_1 = 'title1';
	const DESCRIPTION_1 = 'description1';
	const QUANTITY_1 = 'quantity1';
	const CONDITION_1 = 'condition1';
	const PRICE_1 = 'price1';
	const STATUS_1 = 'active';
	const ITEM_ID_2 = 2;
	const TITLE_2 = 'title2';
	const DESCRIPTION_2 = 'description2';
	const QUANTITY_2 = 'quantity2';
	const CONDITION_2 = 'condition2';
	const PRICE_2 = 'price2';
	const STATUS_2 = 'active';
	const ITEM_ID_3 = 3;
	const TITLE_3 = 'title3';
	const DESCRIPTION_3 = 'description3';
	const QUANTITY_3 = 'quantity3';
	const CONDITION_3 = 'condition3';
	const PRICE_3 = 'price3';
	const STATUS_3 = 'active';
	const ITEM_ID_4 = 4;
	const TITLE_4 = 'title4';
	const DESCRIPTION_4 = 'description4';
	const QUANTITY_4 = 'quantity4';
	const CONDITION_4 = 'condition4';
	const PRICE_4 = 'price4';
	const STATUS_4 = 'active';
	const ERROR_ITEM_NOT_EXIST = 'Item does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_ITEM_NOT_CREATED = 'The item was not created!';
	const ERROR_ITEM_NOT_UPDATED = 'The item was not updated!';
	const ERROR_ITEM_NONE = 'Input is required!';
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Insert items.
		$args = [ 
				self::ITEM_ID => self::ITEM_ID_1,
				self::TITLE => self::TITLE_1,
				self::DESCRIPTION => self::DESCRIPTION_1,
				self::QUANTITY => self::QUANTITY_1,
				self::CONDITION => self::CONDITION_1,
				self::PRICE => self::PRICE_1,
				self::STATUS => self::STATUS_1 
		];
		
		$item = new Item ( $pdo, $args );
		try {
			$item->set ();
		} catch ( ModelException $e ) {
		}
		
		$args2 = [ 
				self::ITEM_ID => self::ITEM_ID_2,
				self::TITLE => self::TITLE_2,
				self::DESCRIPTION => self::DESCRIPTION_2,
				self::QUANTITY => self::QUANTITY_2,
				self::CONDITION => self::CONDITION_2,
				self::PRICE => self::PRICE_2,
				self::STATUS => self::STATUS_2 
		];
		
		$item = new Item ( $pdo, $args2 );
		try {
			$item->set ();
		} catch ( ModelException $e ) {
		}
		
		$args3 = [ 
				self::ITEM_ID => self::ITEM_ID_3,
				self::TITLE => self::TITLE_3,
				self::DESCRIPTION => self::DESCRIPTION_3,
				self::QUANTITY => self::QUANTITY_3,
				self::CONDITION => self::CONDITION_3,
				self::PRICE => self::PRICE_3,
				self::STATUS => self::STATUS_3 
		];
		
		$item = new Item ( $pdo, $args3 );
		try {
			$item->set ();
		} catch ( ModelException $e ) {
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new Item ( TestPDO::getInstance () );
	}
	protected function createSutWithId($id) {
		return new Item ( TestPDO::getInstance (), [ 
				self::ITEM_ID => $id 
		] );
	}
	protected function getValidId() {
		return 1;
	}
	protected function getInvalidId() {
		return 200;
	}
	protected function getExpectedAttributesForGet() {
		return [ 
				self::ITEM_ID => self::ITEM_ID_1,
				self::TITLE => self::TITLE_1,
				self::DESCRIPTION => self::DESCRIPTION_1,
				self::QUANTITY => self::QUANTITY_1,
				self::CONDITION => self::CONDITION_1,
				self::PRICE => self::PRICE_1,
				self::STATUS => self::STATUS_1 
		];
	}
	public function testAttributes(): void {
		$values = [ 
				self::ITEM_ID => self::ITEM_ID_1,
				self::TITLE => self::TITLE_1,
				self::DESCRIPTION => self::DESCRIPTION_1,
				self::QUANTITY => self::QUANTITY_1,
				self::CONDITION => self::CONDITION_1,
				self::PRICE => self::PRICE_1,
				self::CREATION_DATE => '1984-08-18',
				self::MODIFIED_DATE => '2015-02-13' 
		];
		
		$this->assertAttributesAreSetAndRetrievedCorrectly ( $values );
	}
	
	/*
	 * get(): Item
	 */
	public function testGetItemNoItemId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOT_EXIST );
		$sut->get ();
	}
	public function testGetItemInvalidItemId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOT_EXIST );
		$sut->get ();
	}
	public function testGetItemValidItemId(): void {
		$sut = $this->createSutWithId ( self::ITEM_ID_2 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
		}
		$this->assertEquals ( self::ITEM_ID_2, $sut->itemID );
		$this->assertEquals ( self::TITLE_2, $sut->title );
		$this->assertEquals ( self::DESCRIPTION_2, $sut->description );
		$this->assertEquals ( self::QUANTITY_2, $sut->quantity );
		$this->assertEquals ( self::CONDITION_2, $sut->itemcondition );
		$this->assertEquals ( self::PRICE_2, $sut->price );
		$this->assertEquals ( self::STATUS_2, $sut->status );
	}
	
	/*
	 * set(): int
	 */
	public function testSetItemEmptyItem(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_NONE );
		$sut->set ();
	}
	public function testSetItemSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->title = self::TITLE_4;
		$sut->description = self::DESCRIPTION_4;
		$sut->quantity = self::QUANTITY_4;
		$sut->itemcondition = self::CONDITION_4;
		$sut->price = self::PRICE_4;
		$sut->status = self::STATUS_4;
		try {
			$sut->itemID = $sut->set ();
		} catch ( ModelException $e ) {
		}
		$sut = $this->createSutWithId ( $sut->itemID );
		try {
			$sut->get ();
			$this->assertEquals ( self::ITEM_ID_4, $sut->itemID );
			$this->assertEquals ( self::TITLE_4, $sut->title );
			$this->assertEquals ( self::DESCRIPTION_4, $sut->description );
			$this->assertEquals ( self::QUANTITY_4, $sut->quantity );
			$this->assertEquals ( self::CONDITION_4, $sut->itemcondition );
			$this->assertEquals ( self::PRICE_4, $sut->price );
			$this->assertEquals ( self::STATUS_4, $sut->status );
		} catch ( ModelException $e ) {
		}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateItemNoItemId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->update ();
	}
	public function testUpdateItemInvalidItemId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->update ();
	}
	public function testUpdateItemNoItem(): void {
		$sut = $this->createSutWithId ( self::ITEM_ID_3 );
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateItemSuccess(): void {
		$sut = $this->createSutWithId ( self::ITEM_ID_3 );
		$sut->title = self::TITLE_4;
		$sut->description = self::DESCRIPTION_4;
		$sut->quantity = self::QUANTITY_4;
		$sut->itemcondition = self::CONDITION_4;
		$sut->price = self::PRICE_4;
		$sut->status = self::STATUS_4;
		$this->assertTrue ( $sut->update () );
		$this->assertEquals ( self::ITEM_ID_3, $sut->itemID );
		$this->assertEquals ( self::TITLE_4, $sut->title );
		$this->assertEquals ( self::DESCRIPTION_4, $sut->description );
		$this->assertEquals ( self::QUANTITY_4, $sut->quantity );
		$this->assertEquals ( self::CONDITION_4, $sut->itemcondition );
		$this->assertEquals ( self::PRICE_4, $sut->price );
		$this->assertEquals ( self::STATUS_4, $sut->status );
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteItemItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteItemItemIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteItemItemIdValid(): void {
		$sut = $this->createSutWithId ( self::ITEM_ID_3 );
		$this->assertTrue ( $sut->delete () );
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * exists(): bool
	 */
	public function testExistsItemItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsItemItemIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsItemItemIdValid(): void {
		$sut = $this->createSutWithId ( self::ITEM_ID_2 );
		$this->assertTrue ( $sut->exists () );
	}
}
