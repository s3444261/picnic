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
require_once dirname(__FILE__) . '/../../../model/Comment.php';
require_once dirname(__FILE__) . '/../../../model/Item.php';
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/Note.php';
require_once dirname(__FILE__) . '/../../../model/ItemNotes.php';

class ItemNotesTest extends PicnicTestCase {

	const ITEM_ID          = 'itemID';
	const USER_ID          = 'userID';
	const NOTE_ID          = 'noteID';
	const ITEM_NOTE_ID     = 'item_noteID';

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);

		// insert a user
		$user = new User($pdo);
		$user->user = 'grant';
		$user->email = 'grant@kinkead.net';
		$user->password = 'TestTest88';
		$userId =  $user->set ();
		$user->get ();
		$user->activate ();
		$user->status = 'admin';
		$user->password = '';  // So the current password is not updated.
		$user->update ();

		// Insert an item.
		$item = new Item($pdo);
		$itemId = $item->set();

		// Insert three notes against that item.
		for($i = 0; $i < 3; $i++){

			$note = new Note($pdo, [self::ITEM_ID => $itemId, self::USER_ID => $userId]);
			$noteId = $note->set();

			$itemComment = $this->createDefaultSut();
			$itemComment->{self::ITEM_ID} = $itemId;
			$itemComment->{self::NOTE_ID} = $noteId;
			$itemComment->set();
		}
	}

	protected function createDefaultSut(){
		return new ItemNotes(TestPDO::getInstance());
	}

	protected function createSutWithId($id){
		return new ItemNotes(TestPDO::getInstance(), [self::ITEM_NOTE_ID => $id]);
	}

	protected function getValidId() {
		return 1;
	}

	protected function getInvalidId() {
		return 200;
	}

	protected function getExpectedExceptionTypeForUnknownId() {
		return null;
	}
	
	protected function getExpectedExceptionTypeForUnsetId() {
		return null;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::ITEM_NOTE_ID  => 1,
			self::ITEM_ID       => 1,
			self::NOTE_ID       => 1,
		];
	}


	public function testAttributes(): void {
		$values = [
			self::ITEM_NOTE_ID => 1,
			self::ITEM_ID      => 1,
			self::NOTE_ID      => 1,
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testSetResultsInValidId(): void {
		$sut = new ItemNotes(TestPDO::getInstance(), [self::ITEM_ID => 1, self::NOTE_ID => 1]);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{self::ITEM_NOTE_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		$sut = new ItemNotes(TestPDO::getInstance(), [self::ITEM_ID => 1, self::NOTE_ID => 1]);
		$sut->set();

		$sut = new ItemNotes(TestPDO::getInstance(), [self::ITEM_ID => 1, self::NOTE_ID => 1]);
		$this->assertEquals(1, $sut->set());
		$this->assertEquals(1, $sut->{self::ITEM_NOTE_ID});
	}

	public function testCountReturnsTotalNumberOfNotesForItem(): void {
		$sut = $this->createSutWithId(1);
		$sut->get();
		$this->assertEquals(3, $sut->count());
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$item = new Note(TestPDO::getInstance());
		$itemId = $item->set();
		$item->get();

		$sut = $this->createSutWithId(1);
		$sut->get();
		$sut->{self::NOTE_ID} = $itemId;
		$sut->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals(4, $sut->{self::NOTE_ID});
	}
}
