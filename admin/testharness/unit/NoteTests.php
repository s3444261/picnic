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

define('NOTE_ID', 'noteID');
define('NOTE_TEXT', 'note');
define('CREATION_DATE', 'created_at');
define('MODIFIED_DATE', 'updated_at');

class NoteTests extends PicnicTestCase {

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		DatabaseGenerator::Generate();

		// insert a note with ID == 1
		$root = new Note();
		$root->{NOTE_ID} = 1;
		$root->{NOTE_TEXT} = 'hi there, world!';
		$root->set();
	}

	protected function createDefaultSut(){
		return new Note();
	}

	protected function createSutWithId($id){
		return new Note([NOTE_ID => $id]);
	}

	public function testAttributes(): void {
		$values = [
			NOTE_ID        => 1,
			NOTE_TEXT      => 'text1',
			CREATION_DATE  => '1984-08-18',
			MODIFIED_DATE  => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testSetResultsInValidCategoryId(): void {
		$sut = new Note([NOTE_ID => 1, NOTE_TEXT =>'text1']);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{NOTE_ID});
	}

	public function testGet(): void {
		$validId = 1;
		$invalidId = 200;

		$expectedValuesForValidId = [
			NOTE_ID   => 1,
			NOTE_TEXT => 'hi there, world!'
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
		$sut->{NOTE_TEXT} = 'the horror';
		$sut->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals('the horror', $sut->{NOTE_TEXT});
	}
}

