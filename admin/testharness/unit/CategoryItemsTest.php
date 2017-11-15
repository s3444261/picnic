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
 * -- CategoryItems.php Test Blocks: --
 * get(): CategoryItems
 * set(): int
 * update(): bool
 * delete(): bool
 * exists(): bool
 * count(): int
 * getCategoryItemsByPage(int $pageNumber, int $itemsPerPage, string $status): array
 *
 * -- CategoryItems.php Test SubBlocks: --
 * get(): CategoryItems
 * -- testGetCategoryItemIDEmpty(): void
 * -- testGetCategoryItemIDInvalid(): void
 * -- testGetCategoryItemIDValid(): void
 *
 * set(): int
 * -- testSetCategoryIDInvalid(): void
 * -- testSetItemIDInvalid(): void
 * -- testSetCategoryIDItemIDExist(): void
 * -- testSetSuccess(): void
 *
 * update(): bool
 * -- testUpdateCategoryItemIDInvalid(): void
 * -- testUpdateCategoryIDInvalid(): void
 * -- testUpdateItemIDInvalid(): void
 * -- testUpdateCategoryIDItemIDExist(): void
 * -- testUpdateSuccess(): void
 *
 * delete(): bool
 * --testDeleteCategoryItemIDInvalid(): void
 * --testDeleteCategoryItemIDValid(): void
 *
 * deleteItem(): bool
 * --testDeleteItemIDInvalid(): void
 * --testDeleteItemIDValid(): void
 *
 * exists(): bool
 * --testDeleteCategoryItemIDInvalid(): void
 * --testDeleteCategoryItemIDValid(): void
 *
 * count(): int
 * -- testCountCategoryIDEmpty(): void
 * -- testCountCategoryIDInvalid(): void
 * -- testCountCategoryIDValid(): void
 *
 * getCategoryItemsByPage(int $pageNumber, int $itemsPerPage, string $status): array
 * -- testGetCategoryItemsPageNumberZero(): void
 * -- testGetCategoryItemsCategoryItemsPerPageZero(): void
 * -- testGetCategoryItemsSuccess(): void
 *
 */
declare ( strict_types = 1 )
	;

require_once 'TestPDO.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/CategoryItems.php';
require_once dirname ( __FILE__ ) . '/../../../model/Category.php';
require_once dirname ( __FILE__ ) . '/../../../model/Item.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
class CategoryItemsTest extends PHPUnit\Framework\TestCase {
	const CATEGORY_ID = 'categoryID';
	const ITEM_ID = 'itemID';
	const PARENT_ID = 'parentID';
	const CATEGORY_NAME = 'category';
 	const OWNING_USER_ID = 'owningUserID';
 	const TITLE = 'title';
	const DESCRIPTION = 'description';
	const QUANTITY = 'quantity';
	const CONDITION = 'itemcondition';
	const TYPE = 'type';
	const PRICE = 'price';
	const STATUS = 'status';
	const CATEGORY_ID_1 = 1;
	const CATEGORY_1 = 'Category';
	const CATEGORY_ID_2 = 2;
	const PARENT_ID_1 = 1;
	const PARENT_ID_2 = 2;
	const CATEGORY_2 = 'Category2';
	const CATEGORY_ID_3 = 3;
	const CATEGORY_3 = 'Category3';
	const PARENT_ID_3 = 3;
	const CATEGORY_ID_4 = 4;
	const CATEGORY_4 = 'Category4';
	const ITEM_ID_2 = 2;
	const ITEM_ID_3 = 3;
	const ITEM_ID_40 = 40;
	const ITEM_ID_101 = 101;
	const PAGE_NUMBER = 3;
	const ITEMS_PER_PAGE = 6;
	const PAGE_NUMBER_ZERO = 0;
	const ITEMS_PER_PAGE_ZERO = 0;
	const ERROR_ZERO = 'Number must be greater than zero!';
	const ERROR_CATEGORY_ID_NOT_EXIST = 'The categoryID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The itemID does not exist!';
	const ERROR_CATEGORYITEM_EXISTS = 'The categoryItem already exists!';
	
	protected function setUp(): void {
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Populate the Category Table
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		$root->set ();

		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		$c->set ();

		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		$c->set ();

		$c->{self::PARENT_ID} = self::PARENT_ID_2;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_4;
		$c->set ();

		$user = new User($pdo);
		$user->user = "f sfsd fsd f";
		$user->email = "test@test.com";
		$user->password = "fRRR44@fff";
		$user->status = "good";
		$userID = $user->set();

		// Populate the Items Table
		for($i = 1; $i <= 101; $i ++) {
			$item = new Item ( $pdo, [
					self::OWNING_USER_ID => $userID,
					self::TITLE => 'title' . $i,
					self::DESCRIPTION => 'description' . $i,
					self::QUANTITY => 'quantity' . $i,
					self::CONDITION => 'New',
					self::TYPE => 'ForSale',
					self::PRICE => 'price' . $i,
					self::STATUS => '' 
			] );

			$item->set ();
		}
		
		// Populate the CategoryItems Table
		$j = 2;
		for($i = 1; $i <= 100; $i ++) {
			$ci = new CategoryItems ( $pdo, [ 
					self::CATEGORY_ID => $j,
					self::ITEM_ID => $i
			] );
			$ci->set ();

			if ($i % 34 == 0) {
				$j ++;
			}
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new CategoryItems ( TestPDO::getInstance () );
	}
	protected function createSutWithId(int $itemID, int $categoryID) {
		return new CategoryItems ( TestPDO::getInstance (), [ 
				self::ITEM_ID => $itemID,
				self::CATEGORY_ID => $categoryID
		] );
	}

	protected function getValidItemId() {
		return self::ITEM_ID_40;
	}

	protected function getValidCategoryId() {
		return self::CATEGORY_ID_3;
	}

	protected function getInvalidItemId() {
		return 5000;
	}

	protected function getInvalidCategoryId() {
		return 5000;
	}

	/*
	 * set(): int
	 */
	public function testSetCategoryIDInvalid(): void {
		$sut = $this->createSutWithId($this->getValidItemId(), $this->getInvalidCategoryId());
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_ID_NOT_EXIST);
		$sut->set ();
	}
	
	public function testSetItemIDInvalid(): void {
		$sut = $this->createSutWithId ($this->getInvalidItemId(), $this->getValidCategoryId());
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST);
		$sut->set ();
	}

	public function testSetBothIDsInvalid(): void {
		$sut = $this->createSutWithId ($this->getInvalidItemId(), $this->getInvalidCategoryId());
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_ID_NOT_EXIST);
		$sut->set ();
	}

	public function testSetDuplicate(): void {
		$sut = $this->createSutWithId($this->getValidItemId(), $this->getValidCategoryId());
		$this->expectExceptionMessage ( self::ERROR_CATEGORYITEM_EXISTS);
		$sut->set ();
	}
	
	public function testSetSuccess(): void {
		$sut = $this->createSutWithId (self::ITEM_ID_101, $this->getValidCategoryId());
		$this->assertTrue($sut->set());
	}

	/*
	 * delete(): bool
	 */
	
	public function testDeleteItemIDInvalid(): void {
		$sut = $this->createSutWithId ($this->getInvalidItemId(), $this->getValidCategoryId());
		$this->assertFalse($sut->delete());
	}

	public function testDeleteCategoryIDInvalid(): void {
		$sut = $this->createSutWithId ($this->getValidItemId(), $this->getInvalidCategoryId());
		$this->assertFalse($sut->delete());
	}

	public function testDeleteBothIDsInvalid(): void {
		$sut = $this->createSutWithId ($this->getInvalidItemId(), $this->getInvalidCategoryId());
		$this->assertFalse($sut->delete());
	}

	public function testDeleteBothIDsValid(): void {
		$sut = $this->createSutWithId ($this->getValidItemId(), $this->getValidCategoryId());
		$this->assertTrue($sut->delete());
	}
		
	/*
	 * deleteItem(): bool
	 */
	
	public function testDeleteItemItemIDInvalid(): void {
		$sut = $this->createSutWithId($this->getInvalidItemId(), $this->getValidCategoryId());
		$this->assertFalse($sut->deleteItem());
	}

	public function testDeleteItemCategoryIDInvalid(): void {
		$sut = $this->createSutWithId($this->getValidItemId(), $this->getInvalidCategoryId());
		$this->assertFalse($sut->deleteItem());
	}

	public function testDeleteItemBothIDsInvalid(): void {
		$sut = $this->createSutWithId($this->getInvalidItemId(), $this->getInvalidCategoryId());
		$this->assertFalse($sut->deleteItem());
	}

	public function testDeleteItemBothIDsValid(): void {
		$sut = $this->createSutWithId($this->getValidItemId(), $this->getValidCategoryId());
		$this->assertTrue($sut->deleteItem());
	}
		
	/*
	 * exists(): bool
	 */

	public function testExistsItemIDInvalid(): void {
		$sut = $this->createSutWithId ($this->getInvalidItemId(), $this->getValidCategoryId());
		$this->assertFalse($sut->exists());
	}

	public function testExistsCategoryIDInvalid(): void {
		$sut = $this->createSutWithId ($this->getValidItemId(), $this->getInvalidCategoryId());
		$this->assertFalse($sut->exists());
	}

	public function testExistsBothIDsInvalid(): void {
		$sut = $this->createSutWithId ($this->getInvalidItemId(), $this->getInvalidCategoryId());
		$this->assertFalse($sut->exists());
	}

	public function testExistsBothIDsValid(): void {
		$sut = $this->createSutWithId($this->getValidItemId(), $this->getValidCategoryId());
		$this->assertTrue($sut->exists());
	}
		
	/*
	 * count(): int
	 */
	public function testCountCategoryIDEmpty(): void {
		$sut = $this->createDefaultSut();
		$this->assertEquals(0, $sut->count());
	}

	public function testCountCategoryIDInvalid(): void {
		$sut = $this->createSutWithId($this->getInvalidItemId(), $this->getInvalidCategoryId());
		$this->assertEquals(0, $sut->count());
	}

	public function testCountCategoryIDValid(): void {
		$sut = $this->createSutWithId($this->getValidItemId (), $this->getValidCategoryId());
		$this->assertEquals ( 34, $sut->count () );
	}
	
	/*
	 * getCategoryItemsByPage(int $pageNumber, int $itemsPerPage, string $status): array
	 */
	public function testGetCategoryItemsPageNumberZero(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ZERO );
		$sut->getCategoryItemsByPage ( self::PAGE_NUMBER_ZERO, self::ITEMS_PER_PAGE, 'ForSale' );
	}

	public function testGetCategoryItemsItemsPerPageZero(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ZERO );
		$sut->getCategoryItemsByPage ( self::PAGE_NUMBER, self::ITEMS_PER_PAGE_ZERO, 'ForSale' );
	}

	public function testGetCategoryItemsSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->categoryID = self::CATEGORY_ID_3;
		try {
			$items = $sut->getCategoryItemsByPage ( self::PAGE_NUMBER, self::ITEMS_PER_PAGE, 'ForSale' );

			$start = 47;
			
			foreach ( $items as $item ) {
				$this->assertEquals ( $start, $item->itemID );
				$start ++;
			}
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
}
