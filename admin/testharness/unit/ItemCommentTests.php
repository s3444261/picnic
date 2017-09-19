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
require_once '../../../model/Comment.php';
require_once '../../../model/Item.php';
require_once '../../../model/User.php';
require_once '../../../model/ItemComments.php';

define('ITEM_ID', 'itemID');
define('USER_ID', 'userID');
define('CATEGORY_ID', 'categoryID');
define('COMMENT_ID', 'commentID');
define('CATEGORY_ITEM_ID', 'category_itemID');
define('ITEM_COMMENT_ID', 'item_commentID');

class ItemCommentTests extends PicnicTestCase {
	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// insert a user with ID == 1
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

		// Insert a single item.
		$item = new Item();
		$itemId = $item->set();

		// Insert three comments against that item.
		for($i = 0; $i < 3; $i++){

			$comment = new Comment([ITEM_ID => $itemId, USER_ID => $userId]);
			$commentId = $comment->set();

			$itemComment = $this->createDefaultSut();
			$itemComment->{ITEM_ID} = $itemId;
			$itemComment->{COMMENT_ID} = $commentId;
			$itemComment->set();
		}
	}

	protected function createDefaultSut(){
		return new ItemComments();
	}

	protected function createSutWithId($id){
		return new ItemComments([ITEM_COMMENT_ID => $id]);
	}

	public function testAttributes(): void {
		$values = [
			ITEM_COMMENT_ID => 1,
			ITEM_ID         => 1,
			COMMENT_ID      => 1,
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testGet(): void {
		$validId = 1;
		$invalidId = 200;

		$expectedValuesForValidId = [
			ITEM_COMMENT_ID => 1,
			ITEM_ID         => 1,
			COMMENT_ID      => 1,
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
		$sut = new ItemComments([ITEM_ID => 1, COMMENT_ID => 1]);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{ITEM_COMMENT_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		// TD To my mind this should fail- doesn't make sense to have a
		// comment mapped to the same item twice.
		$sut = new ItemComments([ITEM_ID => 1, COMMENT_ID => 1]);
		$sut->set();

		$sut = new ItemComments([ITEM_ID => 1, COMMENT_ID => 1]);
		$this->assertEquals(1, $sut->set());
		$this->assertEquals(1, $sut->{ITEM_COMMENT_ID});
	}

	public function testCountReturnsTotalNumberOfCommentsForItem(): void {
		$sut = $this->createSutWithId(1);
		$sut->get();
		$this->assertEquals(3, $sut->count());
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$item = new Item();
		$itemId = $item->set();
		$item->get();

		$sut = $this->createSutWithId(1);
		$sut->get();
		$sut->{ITEM_ID} = $itemId;
		$sut->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals(2, $sut->{ITEM_ID});
	}
}
