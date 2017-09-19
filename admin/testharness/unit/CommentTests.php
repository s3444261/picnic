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
require_once '../../../model/Note.php';
require_once '../../../model/User.php';
require_once '../../../model/Comment.php';

define('COMMENT_ID', 'commentID');
define('USER_ID', 'userID');
define('COMMENT_TEXT', 'comment');
define('CREATION_DATE', 'created_at');
define('MODIFIED_DATE', 'updated_at');

class CommentTests extends PicnicTestCase{
	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// insert a user with ID == 1
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

		// insert a note with ID == 1
		$root = new Comment();
		$root->{COMMENT_ID} = 1;
		$root->{USER_ID} = 1;
		$root->{COMMENT_TEXT} = 'hi there, world!';
		$root->set();
	}

	protected function createDefaultSut(){
		return new Comment();
	}

	protected function createSutWithId($id){
		return new Comment([COMMENT_ID => $id]);
	}

	public function testAttributes(): void {
		$values = [
			COMMENT_ID    => 2,
			USER_ID       => 1,
			COMMENT_TEXT  => 'heeeeeeres johhny!',
			CREATION_DATE => '1984-08-18',
			MODIFIED_DATE => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testGet(): void {
		$validId = 1;
		$invalidId = 200;

		$expectedValuesForValidId = [
			COMMENT_ID   => 1,
			USER_ID		 => 1,
			COMMENT_TEXT => 'hi there, world!'
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

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$sut = $this->createSutWithId(1);
		$sut->get();
		$sut->{COMMENT_TEXT} = 'be excellent to each other';
		$sut->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals('be excellent to each other', $sut->{COMMENT_TEXT});
	}

	function testCountReturnsTotalNumberOfCommentsForUser(): void {

		$sut = $this->createSutWithId(1);
		$sut->get();
		$this->assertEquals(1, $sut->count());
	}
}
