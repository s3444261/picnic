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
require_once dirname(__FILE__) . '/../../../model/Comment.php';
require_once dirname(__FILE__) . '/../../../model/Item.php';
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/ItemComments.php';

class ItemCommentTest extends PicnicTestCase {

	const ITEM_ID          = 'itemID';
	const USER_ID          = 'userID';
	const CATEGORY_ID      = 'categoryID';
	const COMMENT_ID       = 'commentID';
	const CATEGORY_ITEM_ID = 'category_itemID';
	const ITEM_COMMENT_ID  = 'item_commentID';

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

			$comment = new Comment([self::ITEM_ID => $itemId, self::USER_ID => $userId]);
			$commentId = $comment->set();

			$itemComment = $this->createDefaultSut();
			$itemComment->{self::ITEM_ID} = $itemId;
			$itemComment->{self::COMMENT_ID} = $commentId;
			$itemComment->set();
		}
	}

	protected function createDefaultSut(){
		return new ItemComments();
	}

	protected function createSutWithId($id){
		return new ItemComments([self::ITEM_COMMENT_ID => $id]);
	}

	public function testAttributes(): void {
		$values = [
			self::ITEM_COMMENT_ID => 1,
			self::ITEM_ID         => 1,
			self::COMMENT_ID      => 1,
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testGet(): void {
		$validId = 1;
		$invalidId = 200;

		$expectedValuesForValidId = [
			self::ITEM_COMMENT_ID => 1,
			self::ITEM_ID         => 1,
			self::COMMENT_ID      => 1,
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
		$sut = new ItemComments([self::ITEM_ID => 1, self::COMMENT_ID => 1]);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{self::ITEM_COMMENT_ID});
	}

	public function testSetForDuplicateCombinationReturnsNewId(): void {
		// TD To my mind this should fail- doesn't make sense to have a
		// comment mapped to the same item twice.
		$sut = new ItemComments([self::ITEM_ID => 1, self::COMMENT_ID => 1]);
		$sut->set();

		$sut = new ItemComments([self::ITEM_ID => 1, self::COMMENT_ID => 1]);
		$this->assertEquals(1, $sut->set());
		$this->assertEquals(1, $sut->{self::ITEM_COMMENT_ID});
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
		$sut->{self::ITEM_ID} = $itemId;
		$sut->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals(2, $sut->{self::ITEM_ID});
	}
}
