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
require_once 'PicnicTestCaseOld.php';
require_once dirname(__FILE__) . '/../../createDB/DatabaseGenerator.php';
require_once dirname(__FILE__) . '/../../../model/Note.php';
require_once dirname(__FILE__) . '/../../../model/NoteException.php';

class NoteTest extends PicnicTestCaseOld {

	const NOTE_ID = 'noteID';
	const NOTE_TEXT = 'note';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';

	protected function setUp(): void {
		// Regenerate a fresh database. This makes the tests sloooooooooooow but robust.
		// Be nice if we could mock out the database, but let's see how we go with that.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);

		// Insert a note.
		$root = new Note($pdo);
		$root->{self::NOTE_ID} = 1;
		$root->{self::NOTE_TEXT} = 'hi there, world!';
		$root->set();
	}

	protected function tearDown(): void {
		TestPDO::CleanUp();
	}

	protected function createDefaultSut(){
		return new Note(TestPDO::getInstance());
	}

	protected function createSutWithId($id){
		return new Note(TestPDO::getInstance(), [self::NOTE_ID => $id]);
	}

	protected function getValidId() {
		return 1;
	}

	protected function getInvalidId() {
		return 200;
	}

	protected function getExpectedExceptionTypeForUnsetId() {
		return NoteException::class;
	}
	
	protected function getExpectedExceptionTypeForUnknownId() {
		return NoteException::class;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::NOTE_ID   => 1,
			self::NOTE_TEXT => 'hi there, world!'
		];
	}

	public function testAttributes(): void {
		$values = [
			self::NOTE_ID        => 1,
			self::NOTE_TEXT      => 'text1',
			self::CREATION_DATE  => '1984-08-18',
			self::MODIFIED_DATE  => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	public function testSetResultsInValidId(): void {
		$sut = new Note(TestPDO::getInstance(), [self::NOTE_ID => 1, self::NOTE_TEXT =>'text1']);
		$this->assertGreaterThan(0, $sut->set());
		$this->assertGreaterThan(0, $sut->{self::NOTE_ID});
	}

	public function testUpdateIsCorrectlyReflectedInSubsequentGet(): void {
		$sut = $this->createSutWithId(1);
		$sut->get();
		$sut->{self::NOTE_TEXT} = 'the horror';
		$sut->update();

		$sut = $this->createSutWithId(1);
		$sut->get();

		$this->assertEquals('the horror', $sut->{self::NOTE_TEXT});
	}
}

