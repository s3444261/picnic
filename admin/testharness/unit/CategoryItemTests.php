<?php
/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

declare(strict_types=1);

require_once 'PicnicTestCase.php';
require_once '../../createDB/DatabaseGenerator.php';
require_once '../../../config/Picnic.php';
require_once '../../../model/Category.php';
require_once '../../../model/Item.php';
require_once '../../../model/CategoryItems.php';

define('CATEGORY_ID', 'categoryID');
define('PARENT_ID', 'parentID');
define('CATEGORY_NAME', 'category');

define('ITEM_ID', 'itemID');
define('CATEGORY_ITEM_ID', 'category_itemID');

class CategoryItemTests extends PicnicTestCase {

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// insert a root category with ID == 1
		$root = new Category();
		$root->{CATEGORY_ID} = 1;
		$root->{CATEGORY_NAME} = 'Category';
		$root->{PARENT_ID} = 0;
		$root->set();

		// Insert three new items, and map each to the root category.
		for($i = 0; $i < 3; $i++){
			$item = new Item();
			$itemId = $item->set();
			$item->get();

			$categoryItem = new CategoryItems([ITEM_ID => $itemId, CATEGORY_ID => 1]);
			$categoryItem ->set();
			$categoryItem ->get();
		}
	}

	protected function createDefaultSut(){
		return new CategoryItems();
	}

	protected function createSutWithId($id){
		return new CategoryItems([CATEGORY_ITEM_ID => $id]);
	}

	public function testAttributes(): void {
		$values = [
			CATEGORY_ITEM_ID => 2,
			CATEGORY_ID      => 1,
			ITEM_ID          => 1,
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testGet(): void {
		$validId = 2;
		$invalidId = 200;

		$expectedValuesForValidId = [
			CATEGORY_ITEM_ID =>2,
			CATEGORY_ID => 1,
			ITEM_ID => 2
		];

		$this->assertGetIsFunctional($validId, $invalidId, $expectedValuesForValidId);
	}

	public function testExists(): void {
		$validId = 1;
		$invalidId = 200;
		$this->assertExistsIsFunctional($validId, $invalidId);
	}

	public function testDelete(): void {
		$validId = 1;
		$invalidId = 200;
		$this->assertDeleteIsFunctional($validId, $invalidId);
	}

	public function testCountReturnsTotalNumberOfItemsInCategory(): void {
		// TD: count() should probably be a member of the Category class
		// rather than this one.
		$sut = new CategoryItems([ CATEGORY_ITEM_ID => 1]);
		$sut->get();
		$this->assertEquals(3, $sut->count());
	}

	public function testSetResultsInValidCategoryId(): void {
		$sut = new CategoryItems([CATEGORY_ID => 1, ITEM_ID => 1]);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{CATEGORY_ITEM_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		// TD To my mind this should fail- doesn't make sense to have an
		// item mapped to the same category twice.
		$sut = new CategoryItems([CATEGORY_ID => 1, ITEM_ID => 1]);
		$sut->set();

		$sut = new CategoryItems([CATEGORY_ID => 1, ITEM_ID => 1]);
		$this->assertEquals(1, $sut->set());
		$this->assertEquals(1, $sut->{CATEGORY_ITEM_ID});
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$item = new Item();
		$itemId = $item->set();
		$item->get();

		$sut = new CategoryItems([CATEGORY_ITEM_ID => 1]);
		$sut->get();
		$sut->{ITEM_ID} = $itemId;
		$sut->update();

		$sut = new CategoryItems([CATEGORY_ITEM_ID => 1]);
		$sut->get();

		$this->assertEquals(4, $sut->{ITEM_ID});
	}
}
