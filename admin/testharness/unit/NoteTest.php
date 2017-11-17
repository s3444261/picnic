<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

/*
 * TEST SUMMARY
 *
 * -- Note.php Test Blocks: --
 * get(): Note
 * set(): int
 * update(): bool
 * delete(): bool
 * exists(): bool
 *
 * -- Note.php Test SubBlocks: --
 * get(): Note
 * -- testGetNoteNoNoteId(): void
 * -- testGetNoteInvalidNoteId(): void
 * -- testGetNoteValidNoteId():
 *
 * set(): int
 * -- testSetNoteEmptyNote(): void
 * -- testSetNoteSuccess(): void
 *
 * update(): bool
 * -- testUpdateNoteNoNoteId(): void
 * -- testUpdateNoteInvalidNoteId(): void
 * -- testUpdateNoteEmptyNote(): void
 * -- testUpdateNoteSuccess(): void
 *
 * delete(): bool
 * -- testDeleteNoteNoteIdEmpty(): void
 * -- testDeleteNoteNoteIdInvalid(): void
 * -- testDeleteNoteNoteIdValid(): void
 *
 * exists(): bool
 * -- testExistsNoteNoteIdEmpty(): void
 * -- testExistsNoteNoteIdInvalid(): void
 * -- testExistsNoteNoteIdValid(): void
 */
declare ( strict_types = 1 )
	;

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/Note.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
final class NoteTest extends PicnicTestCase {
	const NOTE_ID = 'noteID';
	const NOTE = 'note';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	const NOTE_ID_1 = 1;
	const NOTE_1 = 'Note1';
	const NOTE_ID_2 = 2;
	const NOTE_2 = 'Note2';
	const NOTE_ID_3 = 3;
	const NOTE_3 = 'Note3';
	const NOTE_ID_4 = 4;
	const NOTE_4 = 'Note4';
	const ERROR_NOTE_ID_NOT_EXIST = 'The NoteID does not exist!';
	const ERROR_NOTE_NOT_CREATED = 'The note was not created!';
	const ERROR_NOTE_NOT_UPDATED = 'The note was not updated!';
	const ERROR_NOTE_NONE = 'Input is required!';
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );
		
		$n1 = new Note ( $pdo, [ 
				self::NOTE => self::NOTE_1 
		] );
		$n2 = new Note ( $pdo, [ 
				self::NOTE => self::NOTE_2 
		] );
		$n3 = new Note ( $pdo, [ 
				self::NOTE => self::NOTE_3 
		] );
		
		try {
			$n1->set ();
			$n2->set ();
			$n3->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	protected function tearDown(): void {
		TestPDO::CleanUp ();
	}
	protected function createDefaultSut() {
		return new Note ( TestPDO::getInstance () );
	}
	protected function createSutWithId(int $id) {
		return new Note ( TestPDO::getInstance (), [ 
				self::NOTE_ID => $id 
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
				self::NOTE_ID => self::NOTE_ID_1,
				self::NOTE => self::NOTE_1 
		];
	}
	public function testAttributes(): void {
		$values = [ 
				self::NOTE_ID => self::NOTE_ID_2,
				self::NOTE => self::NOTE_2,
				self::CREATION_DATE => '1984-08-18',
				self::MODIFIED_DATE => '2015-02-13' 
		];
		
		$this->assertAttributesAreSetAndRetrievedCorrectly ( $values );
	}
	
	/*
	 * get(): Note
	 */
	public function testGetNoteNoNoteId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetNoteInvalidNoteId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetNoteValidNoteId(): void {
		$sut = $this->createSutWithId ( self::NOTE_ID_2 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertEquals ( self::NOTE_ID_2, $sut->noteID );
		$this->assertEquals ( self::NOTE_2, $sut->note );
	}
	
	/*
	 * set(): int
	 */
	public function testSetNoteEmptyNote(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_NONE );
		$sut->set ();
	}
	public function testSetNoteSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->note = self::NOTE_4;
		try {
			$sut->noteID = $sut->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$sut = $this->createSutWithId ( $sut->noteID );
		try {
			$sut->get ();
			$this->assertEquals ( self::NOTE_ID_4, $sut->noteID );
			$this->assertEquals ( self::NOTE_4, $sut->note );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateNoteNoNoteId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->update ();
	}
	public function testUpdateNoteInvalidNoteId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->update ();
	}
	public function testUpdateNoteNoNote(): void {
		$sut = $this->createSutWithId ( self::NOTE_ID_3 );
		$this->assertTrue ( $sut->update () );
	}
	public function testUpdateNoteSuccess(): void {
		$sut = $this->createSutWithId ( self::NOTE_ID_3 );
		$sut->note = self::NOTE_4;
		$this->assertTrue ( $sut->update () );
		$this->assertSame ( self::NOTE_4, $sut->note );
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteNoteNoteIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteNoteNoteIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteNoteNoteIdValid(): void {
		$sut = $this->createSutWithId ( self::NOTE_ID_3 );
		$this->assertTrue ( $sut->delete () );
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * exists(): bool
	 */
	public function testExistsNoteNoteIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsNoteNoteIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsNoteNoteIdValid(): void {
		$sut = $this->createSutWithId ( self::NOTE_ID_2 );
		$this->assertTrue ( $sut->exists () );
	}
}
