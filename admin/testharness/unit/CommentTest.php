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
require_once dirname(__FILE__) . '/../../../model/Note.php';
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/Comment.php';

class CommentTest extends PicnicTestCase{

	const COMMENT_ID    = 'commentID';
	const USER_ID       = 'userID';
	const COMMENT_TEXT   = 'comment';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// Insert a user.
		$user = new User ();
		$user->user = 'grant';
		$user->email = 'grant@kinkead.net';
		$user->password = 'TestTest88';
		$user->set ();
		$user->get ();
		$user->activate ();
		$user->status = 'admin';
		$user->password = '';  // So the current password is not updated.
		$user->update ();

		// Insert a comment and associate it with the user.
		$root = new Comment();
		$root->{self::USER_ID} = 1;
		$root->{self::COMMENT_TEXT} = 'hi there, world!';
		$root->set();
	}

	protected function createDefaultSut(){
		return new Comment();
	}

	protected function createSutWithId($id){
		return new Comment([self::COMMENT_ID => $id]);
	}

	protected function getValidId() {
		return 1;
	}

	protected function getInvalidId() {
		return 200;
	}
	protected function getExpectedExceptionTypeForUnsetId() {
		return null;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::COMMENT_ID   => 1,
			self::USER_ID		 => 1,
			self::COMMENT_TEXT => 'hi there, world!'
		];
	}




	public function testAttributes(): void {
		$values = [
			self::COMMENT_ID    => 2,
			self::USER_ID       => 1,
			self::COMMENT_TEXT  => 'heeeeeeres johhny!',
			self::CREATION_DATE => '1984-08-18',
			self::MODIFIED_DATE => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$sut = $this->createSutWithId(1);
		$sut->get();
		$sut->{self::COMMENT_TEXT} = 'be excellent to each other';
		$sut->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals('be excellent to each other', $sut->{self::COMMENT_TEXT});
	}

	function testCountReturnsTotalNumberOfCommentsForUser(): void {

		$sut = $this->createSutWithId(1);
		$sut->get();
		$this->assertEquals(1, $sut->count());
	}
}
