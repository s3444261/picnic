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
require_once dirname(__FILE__) . '/../../createDB/DatabaseGenerator.php';
require_once dirname(__FILE__) . '/../../../config/Picnic.php';
require_once dirname(__FILE__) . '/../../../model/Category.php';

final class CategoryTest extends PicnicTestCase {

	const CATEGORY_ID   = 'categoryID';
	const PARENT_ID     = 'parentID';
	const CATEGORY_NAME = 'category';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE ='updated_at';

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// Insert a root category
		$root = new Category();
		$root->{self::CATEGORY_NAME} = 'root';
		$root->set();

		// Insert a child category
		$child = new Category();
		$child->{self::PARENT_ID} = 1;
		$child->{self::CATEGORY_NAME} = 'child';
		$child->set();
	}

	protected function createDefaultSut(){
		return new Category();
	}

	protected function createSutWithId($id){
		return new Category([self::CATEGORY_ID => $id]);
	}

	public function testAttributes(): void {
		$values = [
			self::CATEGORY_ID   => 1,
			self::PARENT_ID     => 1,
			self::CATEGORY_NAME => 'text1',
			self::CREATION_DATE => '1984-08-18',
			self::MODIFIED_DATE => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testGet(): void {
		$validId = 1;
		$invalidId = 200;

		$expectedValuesForValidId = [
			self::CATEGORY_ID   => 1,
			self::PARENT_ID     => 0,
			self::CATEGORY_NAME => 'root'
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

	public function testSetResultsInValidId(): void {
		$sut = new Category([self::PARENT_ID => 1, self::CATEGORY_NAME => 'child2']);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{self::CATEGORY_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		$sut = new Category([self::PARENT_ID => 1, self::CATEGORY_NAME => 1]);
		$this->assertEquals(3, $sut->set());
		$this->assertEquals(3, $sut->{self::CATEGORY_ID});
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$sut = new Category([self::CATEGORY_ID => 1]);
		$sut->get();
		$sut->{self::CATEGORY_NAME} = 'updated';
		$sut->update();

		$sut = new Category([self::CATEGORY_ID => 1]);
		$sut->get();

		$this->assertEquals('updated', $sut->{self::CATEGORY_NAME});
	}

}
