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
	const CATEGORY_ITEM_ID = 'category_itemID';
	const CATEGORY_ID = 'categoryID';
	const ITEM_ID = 'itemID';
	const PARENT_ID = 'parentID';
	const CATEGORY_NAME = 'category';
 	const OWNING_USER_ID = 'owningUserID';
 	const TITLE = 'title';
	const DESCRIPTION = 'description';
	const QUANTITY = 'quantity';
	const CONDITION = 'itemcondition';
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
	const CATEGORY_ITEM_ID_101 = 101;
	const PAGE_NUMBER = 3;
	const ITEMS_PER_PAGE = 6;
	const PAGE_NUMBER_ZERO = 0;
	const ITEMS_PER_PAGE_ZERO = 0;
	const ERROR_ZERO = 'Number must be greater than zero!';
	const ERROR_CATEGORY_ID_NOT_EXIST = 'The categoryID does not exist!';
	const ERROR_CATEGORY_ITEMS_ID_NOT_EXIST = 'The category_itemsID does not exist!';
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
		try {
			$root->set ();
		} catch ( CategoryException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		try {
			$c->set ();
		} catch ( CategoryException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		try {
			$c->set ();
		} catch ( CategoryException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$c->{self::PARENT_ID} = self::PARENT_ID_2;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_4;
		try {
			$c->set ();
		} catch ( CategoryException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}

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
					self::CONDITION => 'condition' . $i,
					self::PRICE => 'price' . $i,
					self::STATUS => '' 
			] );
			try {
				$item->set ();
			} catch ( ModelException $e ) {
				$this->assertEquals('Exception', $e->getMessage());
			}
		}
		
		// Populate the CategoryItems Table
		$j = 2;
		for($i = 1; $i <= 100; $i ++) {
			$ci = new CategoryItems ( $pdo, [ 
					self::CATEGORY_ID => $j,
					SELF::ITEM_ID => $i 
			] );
			try {
				$ci->set ();
			} catch ( ModelExceptionException $e ) {
				$this->assertEquals('Exception', $e->getMessage());
			}
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
	protected function createSutWithId($id) {
		return new CategoryItems ( TestPDO::getInstance (), [ 
				self::CATEGORY_ITEM_ID => $id 
		] );
	}
	protected function getValidId() {
		return self::CATEGORY_ID_3;
	}
	protected function getInvalidId() {
		return 5000;
	}
	
	/*
	 * get(): CategoryItems
	 */
	public function testGetCategoryItemIDEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_ITEMS_ID_NOT_EXIST);
		$sut->get ();
	}
	public function testGetCategoryItemIDInvalid(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_ITEMS_ID_NOT_EXIST);
		$sut->get ();
	}
	public function testGetCategoryItemIDValid(): void {
		$sut = $this->createSutWithId($this->getValidId());
		try {
			$this->assertEquals ( 2, $sut->get()->categoryID);
			$this->assertEquals ( 3, $sut->get()->itemID);
		} catch ( Exception $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * set(): int
	 */
	public function testSetCategoryIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->categoryID = $this->getInvalidId();
		$sut->itemID = $this->getValidId();
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_ID_NOT_EXIST);
		$sut->set ();
	}
	
	public function testSetItemIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->categoryID = $this->getValidId();
		$sut->itemID = $this->getInvalidId();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST);
		$sut->set ();
	}
	
	public function testSetCategoryIDItemIDExist(): void {
		$sut = $this->createDefaultSut ();
		$sut->categoryID = $this->getValidId();
		$sut->itemID = self::ITEM_ID_40;
		$this->expectExceptionMessage ( self::ERROR_CATEGORYITEM_EXISTS);
		$sut->set ();
	}
	
	public function testSetSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->categoryID = $this->getValidId();
		$sut->itemID = self::ITEM_ID_101;
		try {
			$this->assertEquals ( self::CATEGORY_ITEM_ID_101, $sut->set());
		} catch ( Exception $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * update(): bool
	 */	
	public function testUpdateCategoryItemIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getInvalidId();
		$sut->categoryID = $this->getValidId();
		$sut->itemID = $this->getValidId();
		$this->assertFalse($sut->update());
	}
	
	public function testUpdateCategoryIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getValidId();
		$sut->categoryID = $this->getInvalidId();
		$sut->itemID = $this->getValidId();
		$this->assertFalse($sut->update());
	}
	
	public function testUpdateItemIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getValidId();
		$sut->categoryID = $this->getValidId();
		$sut->itemID = $this->getInvalidId();
		$this->assertFalse($sut->update());
	}
	
	public function testUpdateCategoryIDItemIDExist(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getValidId();
		$sut->categoryID = self::CATEGORY_ID_2;
		$sut->itemID = self::ITEM_ID_3;
		$this->assertFalse($sut->update());
	}
	
	public function testUpdateSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getValidId();
		$sut->categoryID = self::CATEGORY_ID_3;
		$sut->itemID = self::ITEM_ID_2;
		$this->assertTrue($sut->update());
	}
	
	/*
	 * delete(): bool
	 */
	
	public function testDeleteCategoryItemCategoryItemIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getInvalidId();
		$this->assertFalse($sut->delete());
	}
	
	public function testDeleteCategoryItemCategoryItemIDValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getValidId();
		$this->assertTrue($sut->delete());
	}
		
	/*
	 * deleteItem(): bool
	 */
	
	public function testDeleteCategoryItemItemIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId();
		$this->assertFalse($sut->deleteItem());
	}
	
	public function testDeleteCategoryItemItemIDValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getValidId();
		$this->assertTrue($sut->deleteItem());
	}
		
	/*
	 * exists(): bool
	 */
	
	public function testExistsCategoryItemIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getInvalidId();
		$this->assertFalse($sut->exists());
	}
	
	public function testExistsCategoryItemIDValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->category_itemID = $this->getValidId();
		$this->assertTrue($sut->exists());
	}
		
	/*
	 * count(): int
	 */
	public function testCountCategoryIDEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_ID_NOT_EXIST );
		$sut->count ();
	}
	public function testCountCategoryIDInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->categoryID = $this->getInvalidID ();
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_ID_NOT_EXIST );
		$sut->count ();
	}
	public function testCountCategoryIDValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->categoryID = $this->getValidID ();
		try {
			$this->assertEquals ( 34, $sut->count () );
		} catch ( Exception $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * getCategoryItemsByPage(int $pageNumber, int $itemsPerPage, string $status): array
	 */
	public function testGetCategoryItemsPageNumberZero(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ZERO );
		$sut->getCategoryItemsByPage ( self::PAGE_NUMBER_ZERO, self::ITEMS_PER_PAGE, '' );
	}
	public function testGetCategoryItemsItemsPerPageZero(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ZERO );
		$sut->getCategoryItemsByPage ( self::PAGE_NUMBER, self::ITEMS_PER_PAGE_ZERO, '' );
	}
	public function testGetCategoryItemsSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->categoryID = self::CATEGORY_ID_3;
		try {
			$items = $sut->getCategoryItemsByPage ( self::PAGE_NUMBER, self::ITEMS_PER_PAGE, '' );
			
			$pn = self::PAGE_NUMBER;
			$upp = self::ITEMS_PER_PAGE;
			
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
