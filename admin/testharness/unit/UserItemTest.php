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
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/Item.php';
require_once dirname(__FILE__) . '/../../../model/UserItems.php';
require_once dirname(__FILE__) . '/../../../model/UserException.php';

class UserItemTest extends PicnicTestCase {

	const ITEM_ID      = 'itemID';
	const USER_ID      = 'userID';
	const USER_ITEM_ID = 'user_itemID';

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// Insert a user.
		$user = new User();
		$user->user = 'grant';
		$user->email = 'grant@kinkead.net';
		$user->password = 'TestTest88';
		$userId =  $user->set ();
		$user->get ();
		$user->activate ();
		$user->status = 'admin';
		$user->password = '';  // So the current password is not updated.
		$user->update ();

		// Insert three items and associate them with the user.
		for($i = 0; $i < 3; $i++){
			$item = new Item();
			$itemId = $item->set();

			$itemComment = $this->createDefaultSut();
			$itemComment->{self::ITEM_ID} = $itemId;
			$itemComment->{self::USER_ID} = $userId;
			$itemComment->set();
		}
	}

	protected function createDefaultSut(){
		return new UserItems();
	}

	protected function createSutWithId($id){
		return new UserItems([self::USER_ITEM_ID => $id]);
	}

	protected function getValidId() {
		return 1;
	}

	protected function getInvalidId() {
		return 200;
	}

	protected function getExpectedExceptionTypeForUnsetId() {
		return UserException::class;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::USER_ITEM_ID => 1,
			self::USER_ID      => 1,
			self::ITEM_ID      => 1
		];
	}


	public function testAttributes(): void {
		$values = [
			self::USER_ITEM_ID => 1,
			self::USER_ID      => 1,
			self::ITEM_ID      => 1,
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testCountReturnsTotalNumberOfItemsForUser(): void {
		$sut = $this->createSutWithId(1);
		$sut->get();
		$this->assertEquals(3, $sut->count());
	}

	public function testSetResultsInValidId(): void {
		$sut = new UserItems([self::USER_ID => 1, self::ITEM_ID => 1]);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{self::USER_ITEM_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		$sut = new UserItems([self::USER_ID => 1, self::ITEM_ID => 1]);
		$sut->set();

		$sut = new UserItems([self::USER_ID => 1, self::ITEM_ID => 1]);
		$this->assertEquals(1, $sut->set());
		$this->assertEquals(1, $sut->{self::USER_ITEM_ID});
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$item = new Item();
		$itemId = $item->set();
		$item->get();

		$sut = new UserItems([self::USER_ITEM_ID => 1]);
		$sut->get();
		$sut->{self::ITEM_ID} = $itemId;
		$sut->update();

		$sut = new UserItems([self::USER_ITEM_ID => 1]);
		$sut->get();

		$this->assertEquals(4, $sut->{self::ITEM_ID});
	}
}
