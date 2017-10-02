<?php
/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

declare(strict_types=1);

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname(__FILE__) . '/../../createDB/DatabaseGenerator.php';
require_once dirname(__FILE__) . '/../../../model/Category.php';
require_once dirname(__FILE__) . '/../../../model/Item.php';
require_once dirname(__FILE__) . '/../../../model/CategoryItems.php';

class CategoryItemTest extends PicnicTestCase {

	const CATEGORY_ID      = 'categoryID';
	const PARENT_ID        = 'parentID';
	const CATEGORY_NAME    = 'category';
	const ITEM_ID          = 'itemID';
	const CATEGORY_ITEM_ID = 'category_itemID';

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();

		DatabaseGenerator::Generate($pdo);

		// Insert a category.
		$root = new Category($pdo);
		$root->{self::CATEGORY_ID} = 1;
		$root->{self::CATEGORY_NAME} = 'Category';
		$root->{self::PARENT_ID} = 0;
		$root->set();

		// Insert three new items, and map each to the category.
		for($i = 0; $i < 3; $i++){
			$item = new Item($pdo);
			$itemId = $item->set();
			$item->get();

			$categoryItem = new CategoryItems($pdo, [self::ITEM_ID => $itemId, self::CATEGORY_ID => 1]);
			$categoryItem ->set();
			$categoryItem ->get();
		}
	}

	protected function createDefaultSut(){
		return new CategoryItems(TestPDO::getInstance());
	}

	protected function createSutWithId($id){
		return new CategoryItems(TestPDO::getInstance(), [self::CATEGORY_ITEM_ID => $id]);
	}
	protected function getValidId() {
		return 1;
	}

	protected function getInvalidId() {
		return 200;
	}

	protected function getExpectedExceptionTypeForUnsetId() {
		return null;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::CATEGORY_ITEM_ID =>1,
			self::CATEGORY_ID => 1,
			self::ITEM_ID => 1
		];
	}

	public function testAttributes(): void {
		$values = [
			self::CATEGORY_ITEM_ID => 2,
			self::CATEGORY_ID      => 1,
			self::ITEM_ID          => 1,
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testCountReturnsTotalNumberOfItemsInCategory(): void {
		$sut = new CategoryItems(TestPDO::getInstance(), [ self::CATEGORY_ITEM_ID => 1]);
		$sut->get();
		$this->assertEquals(3, $sut->count());
	}

	public function testSetResultsInValidId(): void {
		$sut = new CategoryItems(TestPDO::getInstance(), [self::CATEGORY_ID => 1, self::ITEM_ID => 1]);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{self::CATEGORY_ITEM_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		$sut = new CategoryItems(TestPDO::getInstance(), [self::CATEGORY_ID => 1, self::ITEM_ID => 1]);
		$sut->set();

		$sut = new CategoryItems(TestPDO::getInstance(), [self::CATEGORY_ID => 1, self::ITEM_ID => 1]);
		$this->assertEquals(1, $sut->set());
		$this->assertEquals(1, $sut->{self::CATEGORY_ITEM_ID});
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$item = new Item(TestPDO::getInstance());
		$itemId = $item->set();
		$item->get();

		$sut = new CategoryItems(TestPDO::getInstance(), [self::CATEGORY_ITEM_ID => 1]);
		$sut->get();
		$sut->{self::ITEM_ID} = $itemId;
		$sut->update();

		$sut = new CategoryItems(TestPDO::getInstance(), [self::CATEGORY_ITEM_ID => 1]);
		$sut->get();

		$this->assertEquals(4, $sut->{self::ITEM_ID});
	}
}
