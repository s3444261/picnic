<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <edwanhp@gmail.com>
 */

/*
 * TEST SUMMARY
 *
 * -- ItemComments.php Test Blocks: --
 * get(): ItemComments
 * set(): int
 * update(): bool
 * delete(): bool
 * exists(): bool
 * existsCommentID(): bool
 * count(): int
 * getItemComments(): array
 * getItemComment(): ItemComments
 * deleteComment(): bool
 * deleteItem(): bool
 * count(): int
 * getItemComments(): array
 * getItemComment(): ItemComments
 * deleteComment(): bool
 * deleteItem(): bool
 *
 * -- ItemComments.php Test SubBlocks: --
 * get(): ItemComments
 * -- testGetItemCommentsNoItemCommentsId(): void
 * -- testGetItemCommentsInvalidItemCommentsId(): void
 * -- testGetItemCommentsValidItemCommentsId():
 *
 * set(): int
 * -- testSetItemCommentsEmpty(): void
 * -- testSetItemCommentsInvalidItemId(): void
 * -- testSetItemCommentsInvalidCommentId(): void
 * -- testSetItemCommentsExistingCommentId(): void
 * -- testSetItemCommentsSuccess(): void
 *
 * update(): bool
 * -- testUpdateItemCommentsNoItemCommentsId(): void
 * -- testUpdateItemCommentsInvalidItemCommentsId(): void
 * -- testUpdateItemCommentsInvalidItemId(): void
 * -- testUpdateItemCommentsInvalidCommentId(): void
 * -- testUpdateItemCommentsExistingCommentId(): void
 * -- testUpdateItemCommentsSuccess(): void
 *
 * delete(): bool
 * -- testDeleteItemCommentsItemCommentsIdEmpty(): void
 * -- testDeleteItemCommentsItemCommentsIdInvalid(): void
 * -- testDeleteItemCommentsItemCommentsIdValid(): void
 *
 * exists(): bool
 * -- testExistsItemCommentsItemCommentsIdEmpty(): void
 * -- testExistsItemCommentsItemCommentsIdInvalid(): void
 * -- testExistsItemCommentsItemCommentsIdValid(): void
 *
 * existsCommentID(): bool
 * -- testExistsItemCommentsCommentIdEmpty(): void
 * -- testExistsItemCommentsCommentIdInvalid(): void
 * -- testExistsItemCommentsCommentIdValid(): void
 *
 * count(): int
 * -- testCountItemIdEmpty(): void
 * -- testCountItemIdInvalid(): void
 * -- testCountItemIdValid(): void
 *
 * getItemComments(): array
 * -- testGetItemCommentsItemIdEmpty(): void
 * -- testGetItemCommentsItemIdInvalid(): void
 * -- testGetItemCommentsItemIdValid(): void
 *
 * getItemComment(): ItemComments
 * -- testGetItemCommentCommentIdEmpty(): void
 * -- testGetItemCommentCommentIdInvalid(): void
 * -- testGetItemCommentCommentIdValid(): void
 *
 * deleteComment(): bool
 * -- testDeleteCommentCommentIdEmpty(): void
 * -- testDeleteCommentCommentIdInvalid(): void
 * -- testDeleteCommentCommentIdValid(): void
 *
 * deleteItem(): bool
 * -- testDeleteItemItemIdEmpty(): void
 * -- testDeleteItemItemIdInvalid(): void
 * -- testDeleteItemItemIdValid(): void
 */
declare ( strict_types = 1 )
	;

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/ItemComments.php';
require_once dirname ( __FILE__ ) . '/../../../model/Item.php';
require_once dirname ( __FILE__ ) . '/../../../model/Comment.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
final class ItemCommentsTest extends PicnicTestCase {
	const ITEM_COMMENT_ID = 'item_commentID';
	const ITEM_ID = 'itemID';
	const COMMENT_ID = 'commentID';
	const USER_ID_1 = 1;
	const ITEM_COMMENT_ID_1 = 1;
	const ITEM_ID_1 = 1;
	const COMMENT_ID_1 = 1;
	const ITEM_COMMENT_ID_2 = 2;
	const ITEM_ID_2 = 2;
	const COMMENT_ID_2 = 2;
	const ITEM_COMMENT_ID_10 = 10;
	const ITEM_COMMENT_ID_15 = 15;
	const ITEM_ID_3 = 3;
	const COMMENT_ID_5 = 5;
	const COMMENT_ID_15 = 15;
	const ERROR_ITEM_COMMENT_ID_NOT_EXIST = 'The ItemCommentID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_COMMENT_ID_NOT_EXIST = 'The CommentID does not exist!';
	const ERROR_COMMENT_ID_ALREADY_EXIST = 'The CommentID is already in Item_comments!';
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		$user = new User ( $pdo, [ 
				'user' => 'user',
				'email' => 'user@gmai.com',
				'password' => 'TestTest88' 
		] );
		try {
			$user->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		$l = 1;
		for($i = 1; $i <= 3; $i ++) {
			$item = new Item ( $pdo );
			$item->owningUserID = $user->userID;
			$item->title = 'title' . $i;
			$item->set ();
			for($j = 1; $j <= 5; $j ++) {
				$comment = new Comment ( $pdo );
				$comment->userID = self::USER_ID_1;
				$comment->comment = 'comment' . $l;
				try {
					$comment->set ();
					$itemComment = new ItemComments ( $pdo );
					$itemComment->itemID = $i;
					$itemComment->commentID = $l;
					try {
						if ($itemComment->itemID == 3 && $itemComment->commentID == 15) {
							// Don't set.
						} else {
							$itemComment->set ();
						}
					} catch ( ModelException $e ) {
						$this->assertEquals('Exception', $e->getMessage());
					}
				} catch ( Exception $e ) {
					$this->assertEquals('Exception', $e->getMessage());
				}
				$l ++;
			}
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new ItemComments ( TestPDO::getInstance () );
	}
	protected function createSutWithId($id) {
		return new ItemComments ( TestPDO::getInstance (), [ 
				self::ITEM_COMMENT_ID => $id 
		] );
	}
	protected function getValidId() {
		return 1;
	}
	protected function getInvalidId() {
		return 2000;
	}
	protected function getExpectedAttributesForGet() {
		return [ 
				self::ITEM_COMMENT_ID => self::COMMENT_ID_1,
				self::ITEM_ID => self::ITEM_ID_1,
				self::COMMENT_ID => self::COMMENT_ID_1 
		];
	}
	public function testAttributes(): void {
		$values = [ 
				self::ITEM_COMMENT_ID => self::COMMENT_ID_2,
				self::ITEM_ID => self::ITEM_ID_2,
				self::COMMENT_ID => self::COMMENT_ID_2 
		];
		
		$this->assertAttributesAreSetAndRetrievedCorrectly ( $values );
	}
	
	/*
	 * get(): ItemComments
	 */
	public function testGetItemCommentsNoItemCommentsId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetItemCommentsInvalidItemCommentsId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetItemCommentsValidItemCommentsId(): void {
		$sut = $this->createSutWithId ( self::ITEM_COMMENT_ID_2 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertEquals ( self::ITEM_COMMENT_ID_2, $sut->item_commentID );
		$this->assertEquals ( self::ITEM_ID_1, $sut->itemID );
		$this->assertEquals ( self::COMMENT_ID_2, $sut->commentID );
	}
	
	/*
	 * set(): int
	 */
	public function testSetItemCommentsEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( '' );
		$sut->set ();
	}
	public function testSetItemCommentsInvalidItemId(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$sut->commentID = self::COMMENT_ID_5;
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->set ();
	}
	public function testSetItemCommentsInvalidCommentId(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_3;
		$sut->commentID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->set ();
	}
	public function testSetItemCommentsExistingCommentId(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_3;
		$sut->commentID = self::COMMENT_ID_2;
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_ALREADY_EXIST );
		$sut->set ();
	}
	public function testSetItemCommentsSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_3;
		$sut->commentID = self::COMMENT_ID_15;
		try {
			$sut->item_commentID = $sut->set ();
		} catch ( ModelException $e ) {
		}
		$sut = $this->createSutWithId ( $sut->item_commentID );
		try {
			$sut->get ();
			$this->assertEquals ( self::ITEM_COMMENT_ID_15, $sut->item_commentID );
			$this->assertEquals ( self::ITEM_ID_3, $sut->itemID );
			$this->assertEquals ( self::COMMENT_ID_15, $sut->commentID );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateItemCommentsNoItemCommentsId(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->update () );
	}
	public function testUpdateItemCommentsInvalidItemCommentsId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->update () );
	}
	public function testUpdateItemCommentsInvalidItemId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->itemID = $this->getInvalidId ();
		$sut->commentID = self::COMMENT_ID_15;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertSame ( '1', $sut->itemID );
		$this->assertSame ( '15', $sut->commentID );
	}
	public function testUpdateItemCommentsInvalidCommentId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->itemID = self::ITEM_ID_2;
		$sut->commentID = $this->getInvalidId ();
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertSame ( '2', $sut->itemID );
		$this->assertSame ( '1', $sut->commentID );
	}
	public function testUpdateItemCommentsExistingCommentId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->itemID = self::ITEM_ID_2;
		$sut->commentID = self::COMMENT_ID_2;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertSame ( '2', $sut->itemID );
		$this->assertSame ( '1', $sut->commentID );
	}
	public function testUpdateItemCommentsSuccess(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->itemID = self::ITEM_ID_2;
		$sut->commentID = self::COMMENT_ID_15;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertSame ( '2', $sut->itemID );
		$this->assertSame ( '15', $sut->commentID );
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteItemCommentsItemCommentsIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteItemCommentsItemCommentsIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteItemCommentsItemCommentsIdValid(): void {
		$sut = $this->createSutWithId ( self::ITEM_COMMENT_ID_2 );
		try {
			$this->assertTrue ( $sut->delete () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->expectExceptionMessage ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * exists(): bool
	 */
	public function testExistsItemCommentsItemCommentsIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsItemCommentsItemCommentsIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsItemCommentsItemCommentsIdValid(): void {
		$sut = $this->createSutWithId ( self::COMMENT_ID_2 );
		$this->assertTrue ( $sut->exists () );
	}
	
	/*
	 * existsCommentID(): bool
	 */
	public function testExistsItemCommentsCommentIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->existsCommentID () );
	}
	public function testExistsItemCommentsCommentIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->commentID = $this->getInvalidId ();
		$this->assertFalse ( $sut->existsCommentID () );
	}
	public function testExistsItemCommentsCommentIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->commentID = $this->getValidId ();
		$this->assertTrue ( $sut->existsCommentID () );
	}
	
	/*
	 * existsItemID(): bool
	 */
	public function testExistsItemCommentsItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->existsItemID () );
	}
	public function testExistsItemCommentsItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->assertFalse ( $sut->existsItemID () );
	}
	public function testExistsItemCommentsItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getValidId ();
		$this->assertTrue ( $sut->existsItemID () );
	}
	
	/*
	 * count(): int
	 */
	public function testCountItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertEquals ( 0, $sut->count () );
	}
	public function testCountItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->assertEquals ( 0, $sut->count () );
	}
	public function testCountItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getValidId ();
		$this->assertEquals ( 5, $sut->count () );
	}
	
	/*
	 * getItemComments(): array
	 */
	public function testGetItemCommentsItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->getItemComments ();
	}
	public function testGetItemCommentsItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->getItemComments ();
	}
	public function testGetItemCommentsItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_2;
		try {
			$i = 6;
			foreach ( $sut->getItemComments () as $obj ) {
				$this->assertEquals ( $i, $obj->item_commentID );
				$this->assertEquals ( 2, $obj->itemID );
				$this->assertEquals ( $i, $obj->commentID );
				$i ++;
			}
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * getItemComment(): ItemComments
	 */
	public function testGetItemCommentCommentIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->getItemComment ();
	}
	public function testGetItemCommentCommentIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->commentID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->getItemComment ();
	}
	public function testGetItemCommentCommentIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->commentID = self::COMMENT_ID_2;
		try {
			$obj = $sut->getItemComment ();
			$this->assertEquals ( 2, $obj->item_commentID );
			$this->assertEquals ( 1, $obj->itemID );
			$this->assertEquals ( 2, $obj->commentID );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * deleteComment(): bool
	 */
	public function testDeleteCommentCommentIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->deleteItemComment ();
	}
	public function testDeleteCommentCommentIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->commentID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->deleteItemComment ();
	}
	public function testDeleteCommentCommentIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->commentID = self::COMMENT_ID_2;
		try {
			$this->assertTrue ( $sut->deleteItemComment () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$sut = $this->createSutWithId ( self::ITEM_COMMENT_ID_2 );
		$this->expectExceptionMessage ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * deleteItemComments(): bool
	 */
	public function testDeleteItemCommentsItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->deleteItemComments ();
	}
	public function testDeleteItemCommentsItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->deleteItemComments ();
	}
	public function testDeleteItemCommentsItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_2;
		try {
			$this->assertTrue ( $sut->deleteItemComments () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		for($i = 6; $i <= 10; $i ++) {
			$sut = $this->createSutWithId ( $i );
			$this->expectExceptionMessage ( self::ERROR_ITEM_COMMENT_ID_NOT_EXIST );
			$sut->get ();
		}
	}
} 
