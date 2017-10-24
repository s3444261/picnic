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
 * getCategoryItems(int $pageNumber, int $usersPerPage): array
 *
 * -- CategoryItems.php Test SubBlocks: --
 * get(): CategoryItems
 * TO DO
 *
 * set(): int
 * TO DO
 *
 * update(): bool
 * TO DO
 *
 * delete(): bool
 * TO DO
 *
 * deleteItem(): bool
 * TO DO
 *
 * exists(): bool
 * TO DO
 *
 * count(): int
 * -- testCountCategoryIDEmpty(): void
 * -- testCountCategoryIDInvalid(): void
 * -- testCountCategoryIDValid(): void
 *
 * getCategoryItems(int $pageNumber, int $usersPerPage): array
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
	const PAGE_NUMBER = 3;
	const ITEMS_PER_PAGE = 6;
	const PAGE_NUMBER_ZERO = 0;
	const ITEMS_PER_PAGE_ZERO = 0;
	const ERROR_ZERO = 'Number must be greater than zero!';
	const ERROR_CATEGORY_ID_NOT_EXIST = 'The categoryID does not exist!';
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
		}
		
		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		try {
			$c->set ();
		} catch ( CategoryException $e ) {
		}
		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		try {
			$c->set ();
		} catch ( CategoryException $e ) {
		}
		$c->{self::PARENT_ID} = self::PARENT_ID_2;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_4;
		try {
			$c->set ();
		} catch ( CategoryException $e ) {
		}

		$user = new User($pdo);
		$user->user = "f sfsd fsd f";
		$user->email = "test@test.com";
		$user->password = "fRRR44@fff";
		$user->status = "good";
		$userID = $user->set();

		// Populate the Items Table
		for($i = 1; $i <= 100; $i ++) {
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
		}
	}
	
	/*
	 * getCategoryItems(int $pageNumber, int $itemsPerPage): array
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
		}
	}
}
