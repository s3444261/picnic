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
 * -- Category.php Test Blocks: --
 * get(): Category
 * set(): int
 * update(): bool
 * delete(): bool
 * exists(): bool
 *
 * -- Category.php Test SubBlocks: --
 * get(): Category
 * -- testGetCategoryNoCategoryId(): void
 * -- testGetCategoryInvalidCategoryId(): void
 * -- testGetCategoryValidCategoryId():
 *
 * set(): int
 * -- testSetCategoryNoParentId(): void
 * -- testSetCategoryInvalidParentId(): void
 * -- testSetCategoryNoCategory(): void
 * -- testSetCategorySuccess(): void
 *
 * update(): bool
 * -- testUpdateCategoryNoCategoryId(): void
 * -- testUpdateCategoryInvalidCategoryId(): void
 * -- testUpdateCategoryNoParentId(): void
 * -- testUpdateCategoryInvalidParentId(): void
 * -- testUpdateCategoryNoCategory(): void
 * -- testUpdateCategoryParentId(): void
 * -- testUpdateCategoryCategory(): void
 * -- testUpdateCategoryAll(): void
 *
 * delete(): bool
 * -- testDeleteCategoryCategoryIdEmpty(): void
 * -- testDeleteCategoryCategoryIdInvalid(): void
 * -- testDeleteCategoryCategoryIdValid(): void
 *
 * exists(): bool
 * -- testExistsCategoryCategoryIdEmpty(): void
 * -- testExistsCategoryCategoryIdInvalid(): void
 * -- testExistsCategoryCategoryIdValid(): void
 */
declare ( strict_types = 1 )
	;

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/Category.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
final class CategoryTest extends PicnicTestCase {
	const CATEGORY_ID = 'categoryID';
	const PARENT_ID = 'parentID';
	const CATEGORY_NAME = 'category';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
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
	const ERROR_CATEGORY_NONE = 'Input is required!';
	const ERROR_CATEGORY_NOT_EXIST = 'Category does not exist!';
	const ERROR_CATEGORY_NOT_CREATED = 'The category was not created!';
	const ERROR_CATEGORY_NOT_UPDATED = 'The category was not updated!';
	const ERROR_PARENT_ID_NONE = 'Input is required!';
	const ERROR_PARENT_ID_NOT_EXIST = 'The parent category does not exist!';
	const ERROR_PARENT_ID_INVALID = 'Input is required!';
	
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::CATEGORY_1;
		try {
			$root->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		// Insert additional categories
		$c = new Category ( $pdo );
		$c->{self::PARENT_ID} = self::PARENT_ID_1;
		$c->{self::CATEGORY_NAME} = self::CATEGORY_2;
		try {
			$c->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$c->{self::CATEGORY_NAME} = self::CATEGORY_3;
		try {
			$c->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new Category ( TestPDO::getInstance () );
	}
	protected function createSutWithId($id) {
		return new Category ( TestPDO::getInstance (), [ 
				self::CATEGORY_ID => $id 
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
				self::CATEGORY_ID => self::CATEGORY_ID_1,
				self::PARENT_ID => self::PARENT_ID_0,
				self::CATEGORY_NAME => self::CATEGORY_1 
		];
	}
	public function testAttributes(): void {
		$values = [ 
				self::CATEGORY_ID => self::CATEGORY_ID_2,
				self::PARENT_ID => self::PARENT_ID_1,
				self::CATEGORY_NAME => self::CATEGORY_2,
				self::CREATION_DATE => '1984-08-18',
				self::MODIFIED_DATE => '2015-02-13' 
		];
		
		$this->assertAttributesAreSetAndRetrievedCorrectly ( $values );
	}
	
	/*
	 * get(): Category
	 */
	public function testGetCategoryNoCategoryId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_NOT_EXIST );
		$sut->get ();
	}
	public function testGetCategoryInvalidCategoryId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_NOT_EXIST );
		$sut->get ();
	}
	public function testGetCategoryValidCategoryId(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_2 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertEquals ( self::CATEGORY_ID_2, $sut->categoryID );
		$this->assertEquals ( self::PARENT_ID_1, $sut->parentID );
		$this->assertEquals ( self::CATEGORY_2, $sut->category );
	}
	
	/*
	 * set(): int
	 */
	public function testSetCategoryNoParentId(): void {
		$sut = $this->createDefaultSut ();
		$sut->category = self::CATEGORY_4;
		$this->assertEquals ( self::CATEGORY_ID_4, $sut->set () );
	}
	public function testSetCategoryInvalidParentId(): void {
		$sut = $this->createDefaultSut ();
		$sut->parentID = $this->getInvalidId ();
		$sut->category = self::CATEGORY_4;
		$this->expectExceptionMessage ( self::ERROR_PARENT_ID_NOT_EXIST );
		$sut->set ();
	}
	public function testSetCategoryNoCategory(): void {
		$sut = $this->createDefaultSut ();
		$sut->parentID = self::PARENT_ID_1;
		$this->expectExceptionMessage ( self::ERROR_CATEGORY_NONE );
		$sut->set ();
	}
	public function testSetCategorySuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->parentID = self::PARENT_ID_3;
		$sut->category = self::CATEGORY_4;
		try {
			$sut->categoryID = $sut->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$sut = $this->createSutWithId ( $sut->categoryID );
		try {
			$sut->get ();
			$this->assertEquals ( self::CATEGORY_ID_4, $sut->categoryID );
			$this->assertEquals ( self::PARENT_ID_3, $sut->parentID );
			$this->assertEquals ( self::CATEGORY_4, $sut->category );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateCategoryNoCategoryId(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->update () );
	}
	public function testUpdateCategoryInvalidCategoryId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->update () );
	}
	public function testUpdateCategoryNoParentId(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_3 );
		$sut->category = self::CATEGORY_4;
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateCategoryInvalidParentId(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_3 );
		$sut->parentID = $this->getInvalidId ();
		$sut->category = self::CATEGORY_4;
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateCategoryNoCategory(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_3 );
		$sut->parentID = self::PARENT_ID_1;
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateCategoryParentId(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_3 );
		$sut->parentID = self::PARENT_ID_2;
		$sut->category = self::CATEGORY_3;
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateCategoryCategory(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_3 );
		$sut->parentID = self::PARENT_ID_1;
		$sut->category = self::CATEGORY_4;
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateCategoryAll(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_3 );
		$sut->parentID = self::PARENT_ID_3;
		$sut->category = self::CATEGORY_4;
		$this->assertTrue ( $sut->update () );
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteCategoryCategoryIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->delete () );
	}
	public function testDeleteCategoryCategoryIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->delete () );
	}
	public function testDeleteCategoryCategoryIdValid(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_3 );
		$this->assertTrue ( $sut->delete () );
	}
	
	/*
	 * exists(): bool
	 */
	public function testExistsCategoryCategoryIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsCategoryCategoryIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsCategoryCategoryIdValid(): void {
		$sut = $this->createSutWithId ( self::CATEGORY_ID_3 );
		$this->assertTrue ( $sut->exists () );
	}
}
