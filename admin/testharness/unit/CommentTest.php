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
 * -- Comment.php Test Blocks: --
 * get(): Comment
 * set(): int
 * update(): bool
 * delete(): bool
 * exists(): bool
 *
 * -- Comment.php Test SubBlocks: --
 * get(): Comment
 * -- testGetCommentNoCommentId(): void
 * -- testGetCommentInvalidCommentId(): void
 * -- testGetCommentValidCommentId():
 *
 * set(): int
 * -- testSetCommentUserIdEmpty(): void
 * -- testSetCommentUserIdInvalid(): void
 * -- testSetCommentEmptyComment(): void
 * -- testSetCommentSuccess(): void
 *
 * update(): bool
 * -- testUpdateCommentNoCommentId(): void
 * -- testUpdateCommentInvalidCommentId(): void
 * -- testSetCommentUserIdEmpty(): void
 * -- testSetCommentUserIdInvalid(): void
 * -- testUpdateCommentEmptyComment(): void
 * -- testUpdateCommentSuccess(): void
 *
 * delete(): bool
 * -- testDeleteCommentCommentIdEmpty(): void
 * -- testDeleteCommentCommentIdInvalid(): void
 * -- testDeleteCommentCommentIdValid(): void
 *
 * exists(): bool
 * -- testExistsCommentCommentIdEmpty(): void
 * -- testExistsCommentCommentIdInvalid(): void
 * -- testExistsCommentCommentIdValid(): void
 */
declare ( strict_types = 1 )
	;

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/Comment.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
final class CommentTest extends PicnicTestCase {
	const COMMENT_ID = 'commentID';
	const USER_ID = 'userID';
	const COMMENT = 'comment';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	const USER = 'user';
	const EMAIL = 'email';
	const PASSWORD = 'password';
	const USER_ID_1 = 1;
	const USER_NAME_1 = 'peter';
	const USER_EMAIL_1 = 'peter@gmail.com';
	const USER_PASSWORD_1 = 'TestTest88';
	const USER_ID_2 = 2;
	const USER_NAME_2 = 'john';
	const USER_EMAIL_2 = 'john@gmail.com';
	const USER_PASSWORD_2 = 'TestTest99';
	const COMMENT_ID_1 = 1;
	const COMMENT_1 = 'Comment1';
	const COMMENT_ID_2 = 2;
	const COMMENT_2 = 'Comment2';
	const COMMENT_ID_3 = 3;
	const COMMENT_3 = 'Comment3';
	const COMMENT_ID_4 = 4;
	const COMMENT_4 = 'Comment4';
	const ERROR_COMMENT_ID_NOT_EXIST = 'The CommentID does not exist!';
	const ERROR_COMMENT_NOT_CREATED = 'The comment was not created!';
	const ERROR_COMMENT_NOT_UPDATED = 'The comment was not updated!';
	const ERROR_COMMENT_NONE = 'Input is required!';
	const ERROR_USER_ID_NOT_EXIST = 'The User ID does not exist!';
	const ERROR_USER_ID_NOT_INT = 'UserID must be an integer!';
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		$u1 = new User ( $pdo, [ 
				self::USER => self::USER_NAME_1,
				self::EMAIL => self::USER_EMAIL_1,
				self::PASSWORD => self::USER_PASSWORD_1 
		] );
		$u2 = new User ( $pdo, [ 
				self::USER => self::USER_NAME_2,
				self::EMAIL => self::USER_EMAIL_2,
				self::PASSWORD => self::USER_PASSWORD_2 
		] );
		$c1 = new Comment ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::COMMENT => self::COMMENT_1 
		] );
		$c2 = new Comment ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::COMMENT => self::COMMENT_2 
		] );
		$c3 = new Comment ( $pdo, [ 
				self::USER_ID => self::USER_ID_1,
				self::COMMENT => self::COMMENT_3 
		] );
		
		try {
			$u1->set ();
			$u2->set ();
			$c1->set ();
			$c2->set ();
			$c3->set ();
		} catch ( ModelException $e ) {
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new Comment ( TestPDO::getInstance () );
	}
	protected function createSutWithId($id) {
		return new Comment ( TestPDO::getInstance (), [ 
				self::COMMENT_ID => $id 
		] );
	}
	protected function getValidId() {
		return 1;
	}
	protected function getInvalidId() {
		return 200;
	}
	protected function getExpectedAttributesForGet() {
		return [ 
				self::COMMENT_ID => self::COMMENT_ID_1,
				self::COMMENT => self::COMMENT_1 
		];
	}
	public function testAttributes(): void {
		$values = [ 
				self::COMMENT_ID => self::COMMENT_ID_2,
				self::COMMENT => self::COMMENT_2,
				self::CREATION_DATE => '1984-08-18',
				self::MODIFIED_DATE => '2015-02-13' 
		];
		
		$this->assertAttributesAreSetAndRetrievedCorrectly ( $values );
	}
	
	/*
	 * get(): Comment
	 */
	public function testGetCommentNoCommentId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetCommentInvalidCommentId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetCommentValidCommentId(): void {
		$sut = $this->createSutWithId ( self::COMMENT_ID_2 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
		}
		$this->assertEquals ( self::COMMENT_ID_2, $sut->commentID );
		$this->assertEquals ( self::COMMENT_2, $sut->comment );
	}
	
	/*
	 * set(): int
	 */
	public function testSetCommentUserIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$sut->comment = self::COMMENT_4;
		$this->expectExceptionMessage ( self::ERROR_USER_ID_NOT_INT );
		$sut->set ();
	}
	public function testSetCommentUserIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = $this->getInvalidId ();
		$sut->comment = self::COMMENT_4;
		$this->expectExceptionMessage ( self::ERROR_USER_ID_NOT_EXIST );
		$sut->set ();
	}
	public function testSetCommentEmptyComment(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = self::USER_ID_1;
		$this->expectExceptionMessage ( self::ERROR_COMMENT_NONE );
		$sut->set ();
	}
	public function testSetCommentSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->userID = self::USER_ID_1;
		$sut->comment = self::COMMENT_4;
		try {
			$sut->commentID = $sut->set ();
		} catch ( ModelException $e ) {
		}
		$sut = $this->createSutWithId ( $sut->commentID );
		try {
			$sut->get ();
			$this->assertEquals ( self::COMMENT_ID_4, $sut->commentID );
			$this->assertEquals ( self::COMMENT_4, $sut->comment );
		} catch ( ModelException $e ) {
		}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateCommentNoCommentId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->update ();
	}
	public function testUpdateCommentInvalidCommentId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->update ();
	}
	public function testUpdateCommentNoComment(): void {
		$sut = $this->createSutWithId ( self::COMMENT_ID_3 );
		$sut->userID = self::USER_ID_1;
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateCommentNoUserId(): void {
		$sut = $this->createSutWithId ( self::COMMENT_ID_3 );
		$sut->comment = self::COMMENT_4;
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateCommentInvalidUserId(): void {
		$sut = $this->createSutWithId ( self::COMMENT_ID_3 );
		$sut->userID = $this->getInvalidId ();
		$sut->comment = self::COMMENT_4;
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateCommentSuccess(): void {
		$sut = $this->createSutWithId ( self::COMMENT_ID_3 );
		$sut->userID = self::USER_ID_2;
		$sut->comment = self::COMMENT_4;
		$this->assertTrue ( $sut->update () );
		$sut = $this->createSutWithId ( self::COMMENT_ID_3 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
		}
		$this->assertSame ( '2', $sut->userID );
		$this->assertSame ( self::COMMENT_4, $sut->comment );
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteCommentCommentIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteCommentCommentIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteCommentCommentIdValid(): void {
		$sut = $this->createSutWithId ( self::COMMENT_ID_3 );
		$this->assertTrue ( $sut->delete () );
		$this->expectExceptionMessage ( self::ERROR_COMMENT_ID_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * exists(): bool
	 */
	public function testExistsCommentCommentIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsCommentCommentIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsCommentCommentIdValid(): void {
		$sut = $this->createSutWithId ( self::COMMENT_ID_2 );
		$this->assertTrue ( $sut->exists () );
	}
}
