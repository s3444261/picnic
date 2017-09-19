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

define('CATEGORY_ID', 'categoryID');
define('PARENT_ID', 'parentID');
define('CATEGORY_NAME', 'category');
define('CREATION_DATE', 'created_at');
define('MODIFIED_DATE', 'updated_at');

final class CategoryTests extends PicnicTestCase {
	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// insert a root category
		$root = new Category();
		$root->{CATEGORY_NAME} = 'root';
		$root->set();

		// insert a child category
		$child = new Category();
		$child->{PARENT_ID} = 1;
		$child->{CATEGORY_NAME} = 'child';
		$child->set();
	}

	protected function createDefaultSut(){
		return new Category();
	}

	protected function createSutWithId($id){
		return new Category([CATEGORY_ID => $id]);
	}

	public function testAttributes(): void {
		$values = [
			CATEGORY_ID   => 1,
			PARENT_ID     => 1,
			CATEGORY_NAME => 'text1',
			CREATION_DATE => '1984-08-18',
			MODIFIED_DATE => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testGet(): void {
		$validId = 1;
		$invalidId = 200;

		$expectedValuesForValidId = [
			CATEGORY_ID   => 1,
			PARENT_ID     => 0,
			CATEGORY_NAME => 'root'
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

	public function testSetResultsInValidCategoryId(): void {
		$sut = new Category([PARENT_ID => 1, CATEGORY_NAME => 'child2']);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{CATEGORY_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		// TD To my mind this should fail- doesn't make sense to have an
		// item mapped to the same category twice.
		$sut = new Category([PARENT_ID => 1, CATEGORY_NAME => 1]);
		$this->assertEquals(3, $sut->set());
		$this->assertEquals(3, $sut->{CATEGORY_ID});
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$sut = new Category([CATEGORY_ID => 1]);
		$sut->get();
		$sut->{CATEGORY_NAME} = 'updated';
		$sut->update();

		$sut = new Category([CATEGORY_ID => 1]);
		$sut->get();

		$this->assertEquals('updated', $sut->{CATEGORY_NAME});
	}

}
