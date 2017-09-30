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
require_once dirname(__FILE__) . '/../../../model/Item.php';

class ItemTest extends PicnicTestCase
{
	const ITEM_ID          	= 'itemID';
	const TITLE 			= 'title';
	const DESCRIPTION 		= 'description';
	const QUANTITY 			= 'quantity';
	const CONDITION 		= 'itemcondition';
	const PRICE 			= 'price';
	const STATUS 			= 'status';
	const CREATION_DATE 	= 'created_at';
	const MODIFIED_DATE 	= 'updated_at';

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// Insert an item.
		$args = [
			self::TITLE 		=> 'title',
			self::DESCRIPTION 	=> 'description',
			self::QUANTITY		=> 24,
			self::CONDITION 	=> 'condition',
			self::PRICE 		=> 'price',
			self::STATUS 		=> 'status',
			self::CREATION_DATE => '1984-08-18',
			self::MODIFIED_DATE => '2015-02-13'
		];

		$item = new Item($args);
		$item->set();
	}

	protected function createDefaultSut() {
		return new Item();
	}

	protected function createSutWithId($id) {
		return new Item([self::ITEM_ID => $id]);
	}

	protected function getValidId() {
		return 1;
	}

	protected function getInvalidId() {
		return 200;
	}

	protected function getExpectedExceptionTypeForUnsetId() {
		return ItemException::class;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::ITEM_ID 		=> 1,
			self::TITLE 		=> 'title',
			self::DESCRIPTION 	=> 'description',
			self::QUANTITY		=> 24,
			self::CONDITION 	=> 'condition',
			self::PRICE 		=> 'price',
			self::STATUS 		=> 'status'
		];
	}


	public function testAttributes(): void {
		$values = [
			self::ITEM_ID 		=> 1,
			self::TITLE 		=> 'title',
			self::DESCRIPTION 	=> 'description',
			self::QUANTITY		=> 24,
			self::CONDITION 	=> 'condition',
			self::PRICE 		=> 'price',
			self::STATUS 		=> 'status',
			self::CREATION_DATE => '1984-08-18',
			self::MODIFIED_DATE => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testSetResultsInValidId(): void {
		$sut = new Item();
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{self::ITEM_ID});
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {

		$sut =  $this->createSutWithId(1);
		$sut->get();
		$sut->{self::TITLE}			= 'newTitle';
		$sut->{self::DESCRIPTION}	= 'newDescription';
		$sut->{self::QUANTITY}		= 995;
		$sut->{self::CONDITION }	= 'newCcnd';
		$sut->{self::PRICE}			= 'newPrice';
		$sut->{self::STATUS} 		= 'newStatus';
		$sut->update();

		$sut = new Item([self::ITEM_ID => 1]);
		$sut->get();

		$this->assertEquals('newTitle', 		$sut->{self::TITLE});
		$this->assertEquals('newDescription', 	$sut->{self::DESCRIPTION});
		$this->assertEquals(995, 				$sut->{self::QUANTITY});
		$this->assertEquals('newCcnd', 			$sut->{self::CONDITION});
		$this->assertEquals('newPrice', 		$sut->{self::PRICE});
		$this->assertEquals('newStatus', 		$sut->{self::STATUS});
	}
}
