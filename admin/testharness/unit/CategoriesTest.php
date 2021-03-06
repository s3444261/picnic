<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

/*
 * TEST SUMMARY
 *
 * -- Categories.php Test Blocks: --
 * getCategories(): array
 * getCategoriesIn(int $parentID): array
 *
 * -- Categories.php Test SubBlocks: --
 * getCategories(): array
 * -- testGetCategories(): void
 *
 * getCategoriesIn(int $parentID): array
 * -- testGetCategoriesInParentIdInvalid(): void
 * -- testGetCategoriesInParentIdValid(): void
 */
declare ( strict_types = 1 )
	;

require_once 'TestPDO.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/Category.php';
require_once dirname ( __FILE__ ) . '/../../../model/Categories.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
final class CategoriesTest extends PHPUnit\Framework\TestCase {
	const CATEGORY_ID = 'categoryID';
	const PARENT_ID = 'parentID';
	const CATEGORY_NAME = 'category';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	const ROOT_CATEGORY_NAME = 'Category';
	const ERROR_PARENT_ID_NOT_EXIST = 'The parentID does not exist!';
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		// Insert a root category
		$root = new Category ( $pdo );
		$root->{self::CATEGORY_NAME} = self::ROOT_CATEGORY_NAME;
		try {
			$root->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		// Insert additional categories
		$j = 1;
		for($i = 1; $i <= 99; $i ++) {
			if ($i % 3 == 0) {
				$j ++;
			}
			$c = new Category ( $pdo );
			$c->{self::PARENT_ID} = $j;
			$c->{self::CATEGORY_NAME} = 'cat' . $i;
			try {
				$c->set ();
			} catch ( ModelException $e ) {
				$this->assertEquals('Exception', $e->getMessage());
			}
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new Categories ( TestPDO::getInstance () );
	}
	protected function createSutWithId($id) {
		return new Categories ( TestPDO::getInstance (), [ 
				self::CATEGORY_ID => $id 
		] );
	}
	protected function getValidId() {
		return 1;
	}
	protected function getInvalidId() {
		return 200;
	}
	
	/*
	 * getCategories(): array
	 */
	public function testGetCategories(): void {
		$sut = $this->createDefaultSut ();
		$cats = $sut->getCategories ();
		$i = 1;
		$j = 0;
		foreach ( $cats as $cat ) {
			if ($i == 1) {
				$this->assertEquals ( $i, $cat->categoryID );
				$this->assertEquals ( $j, $cat->parentID );
				$this->assertEquals ( 'Category', $cat->category );
				$j ++;
			} else {
				$number = $i - 1;
				$this->assertEquals ( $i, $cat->categoryID );
				$this->assertEquals ( $j, $cat->parentID );
				$this->assertSame ( 'cat' . $number, $cat->category );
			}
			if ($i % 3 == 0) {
				$j ++;
			}
			$i ++;
		}
	}
	
	/*
	 * getCategoriesIn(int $parentID): array
	 */
	public function testGetCategoriesInParentIdInvalid(): void {
		$parentID = $this->getInvalidId ();
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_PARENT_ID_NOT_EXIST );
		$sut->getCategoriesIn ( $parentID );
	}
	public function testGetCategoriesInParentIdValid(): void {
		$sut = $this->createDefaultSut ();
		$cats = $sut->getCategoriesIn ( 4 );

		$this->assertEquals ( 3, count($cats));

		// sorted by name.
		$this->assertEquals ( 11, $cats[0]->categoryID );
		$this->assertEquals ( 4, $cats[0]->parentID );
		$this->assertSame ( 'cat10', $cats[0]->category );

		$this->assertEquals ( 12, $cats[1]->categoryID );
		$this->assertEquals ( 4, $cats[1]->parentID );
		$this->assertSame ( 'cat11', $cats[1]->category );

		$this->assertEquals ( 10, $cats[2]->categoryID );
		$this->assertEquals ( 4, $cats[2]->parentID );
		$this->assertSame ( 'cat9', $cats[2]->category );
	}
}
