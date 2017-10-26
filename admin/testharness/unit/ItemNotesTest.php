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
 * -- ItemNotes.php Test Blocks: --
 * get(): ItemNotes
 * set(): int
 * update(): bool
 * delete(): bool
 * exists(): bool
 * existsNoteID(): bool
 * existsItemID(): bool
 * count(): int
 * getItemNotes(): array
 * getItemNote(): ItemNotes
 * deleteNote(): bool
 * deleteItem(): bool
 * count(): int
 * getItemNotes(): array
 * getItemNote(): ItemNotes
 * deleteNote(): bool
 * deleteItem(): bool
 *
 * -- ItemNotes.php Test SubBlocks: --
 * get(): ItemNotes
 * -- testGetItemNotesNoItemNotesId(): void
 * -- testGetItemNotesInvalidItemNotesId(): void
 * -- testGetItemNotesValidItemNotesId():
 *
 * set(): int
 * -- testSetItemNotesEmpty(): void
 * -- testSetItemNotesInvalidItemId(): void
 * -- testSetItemNotesInvalidNoteId(): void
 * -- testSetItemNotesExistingNoteId(): void
 * -- testSetItemNotesSuccess(): void
 *
 * update(): bool
 * -- testUpdateItemNotesNoItemNotesId(): void
 * -- testUpdateItemNotesInvalidItemNotesId(): void
 * -- testUpdateItemNotesInvalidItemId(): void
 * -- testUpdateItemNotesInvalidNoteId(): void
 * -- testUpdateItemNotesExistingNoteId(): void
 * -- testUpdateItemNotesSuccess(): void
 *
 * delete(): bool
 * -- testDeleteItemNotesItemNotesIdEmpty(): void
 * -- testDeleteItemNotesItemNotesIdInvalid(): void
 * -- testDeleteItemNotesItemNotesIdValid(): void
 *
 * exists(): bool
 * -- testExistsItemNotesItemNotesIdEmpty(): void
 * -- testExistsItemNotesItemNotesIdInvalid(): void
 * -- testExistsItemNotesItemNotesIdValid(): void
 *
 * existsNoteID(): bool
 * -- testExistsItemNotesNoteIdEmpty(): void
 * -- testExistsItemNotesNoteIdInvalid(): void
 * -- testExistsItemNotesNoteIdValid(): void
 *
 * existsItemID(): bool
 * -- testExistsItemNotesItemEmpty(): void
 * -- testExistsItemNotesItemInvalid(): void
 * -- testExistsItemNotesItemValid(): void
 *
 * count(): int
 * -- testCountItemIdEmpty(): void
 * -- testCountItemIdInvalid(): void
 * -- testCountItemIdValid(): void
 *
 * getItemNotes(): array
 * -- testGetItemNotesItemIdEmpty(): void
 * -- testGetItemNotesItemIdInvalid(): void
 * -- testGetItemNotesItemIdValid(): void
 *
 * getItemNote(): ItemNotes
 * -- testGetItemNoteNoteIdEmpty(): void
 * -- testGetItemNoteNoteIdInvalid(): void
 * -- testGetItemNoteNoteIdValid(): void
 *
 * deleteNote(): bool
 * -- testDeleteNoteNoteIdEmpty(): void
 * -- testDeleteNoteNoteIdInvalid(): void
 * -- testDeleteNoteNoteIdValid(): void
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
require_once dirname ( __FILE__ ) . '/../../../model/ItemNotes.php';
require_once dirname ( __FILE__ ) . '/../../../model/Item.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/Note.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ModelException.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';
final class ItemNotesTest extends PicnicTestCase {
	const ITEM_NOTE_ID = 'item_noteID';
	const ITEM_ID = 'itemID';
	const NOTE_ID = 'noteID';
	const ITEM_NOTE_ID_1 = 1;
	const ITEM_ID_1 = 1;
	const NOTE_ID_1 = 1;
	const ITEM_NOTE_ID_2 = 2;
	const ITEM_ID_2 = 2;
	const NOTE_ID_2 = 2;
	const ITEM_NOTE_ID_10 = 10;
	const ITEM_NOTE_ID_15 = 15;
	const ITEM_ID_3 = 3;
	const NOTE_ID_5 = 5;
	const NOTE_ID_15 = 15;
	const ERROR_ITEM_NOTE_ID_NOT_EXIST = 'The ItemNoteID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_NOTE_ID_NOT_EXIST = 'The NoteID does not exist!';
	const ERROR_NOTE_ID_ALREADY_EXIST = 'The NoteID is already in Item_notes!';
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );

		$user = new User($pdo);
		$user->user = "f sfsd fsd f";
		$user->email = "test@test.com";
		$user->password = "fRRR44@fff";
		$user->status = "good";
		$userID = $user->set();

		$l = 1;
		for($i = 1; $i <= 3; $i ++) {
			$item = new Item ( $pdo );
			$item->owningUserID = $userID;
			$item->title = 'title' . $i;
			$item->set ();
			for($j = 1; $j <= 5; $j ++) {
				$note = new Note ( $pdo );
				$note->note = 'note' . $l;
				try {
					$note->set ();
					$itemNote = new ItemNotes ( $pdo );
					$itemNote->itemID = $i;
					$itemNote->noteID = $l;
					try {
						if ($itemNote->itemID == 3 && $itemNote->noteID == 15) {
							// Don't set.
						} else {
							$itemNote->set ();
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
		return new ItemNotes ( TestPDO::getInstance () );
	}
	protected function createSutWithId($id) {
		return new ItemNotes ( TestPDO::getInstance (), [ 
				self::ITEM_NOTE_ID => $id 
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
				self::ITEM_NOTE_ID => self::NOTE_ID_1,
				self::ITEM_ID => self::ITEM_ID_1,
				self::NOTE_ID => self::NOTE_ID_1 
		];
	}
	public function testAttributes(): void {
		$values = [ 
				self::ITEM_NOTE_ID => self::NOTE_ID_2,
				self::ITEM_ID => self::ITEM_ID_2,
				self::NOTE_ID => self::NOTE_ID_2 
		];
		
		$this->assertAttributesAreSetAndRetrievedCorrectly ( $values );
	}
	
	/*
	 * get(): ItemNotes
	 */
	public function testGetItemNotesNoItemNotesId(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetItemNotesInvalidItemNotesId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
		$sut->get ();
	}
	public function testGetItemNotesValidItemNotesId(): void {
		$sut = $this->createSutWithId ( self::ITEM_NOTE_ID_2 );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertEquals ( self::ITEM_NOTE_ID_2, $sut->item_noteID );
		$this->assertEquals ( self::ITEM_ID_1, $sut->itemID );
		$this->assertEquals ( self::NOTE_ID_2, $sut->noteID );
	}
	
	/*
	 * set(): int
	 */
	public function testSetItemNotesEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( '' );
		$sut->set ();
	}
	public function testSetItemNotesInvalidItemId(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$sut->noteID = self::NOTE_ID_5;
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->set ();
	}
	public function testSetItemNotesInvalidNoteId(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_3;
		$sut->noteID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->set ();
	}
	public function testSetItemNotesExistingNoteId(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_3;
		$sut->noteID = self::NOTE_ID_2;
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_ALREADY_EXIST );
		$sut->set ();
	}
	public function testSetItemNotesSuccess(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_3;
		$sut->noteID = self::NOTE_ID_15;
		try {
			$sut->item_noteID = $sut->set ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$sut = $this->createSutWithId ( $sut->item_noteID );
		try {
			$sut->get ();
			$this->assertEquals ( self::ITEM_NOTE_ID_15, $sut->item_noteID );
			$this->assertEquals ( self::ITEM_ID_3, $sut->itemID );
			$this->assertEquals ( self::NOTE_ID_15, $sut->noteID );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateItemNotesNoItemNotesId(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->update () );
	}
	public function testUpdateItemNotesInvalidItemNotesId(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->update () );
	}
	public function testUpdateItemNotesInvalidItemId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->itemID = $this->getInvalidId ();
		$sut->noteID = self::NOTE_ID_15;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertSame ( '1', $sut->itemID );
		$this->assertSame ( '15', $sut->noteID );
	}
	public function testUpdateItemNotesInvalidNoteId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->itemID = self::ITEM_ID_2;
		$sut->noteID = $this->getInvalidId ();
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertSame ( '2', $sut->itemID );
		$this->assertSame ( '1', $sut->noteID );
	}
	public function testUpdateItemNotesExistingNoteId(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->itemID = self::ITEM_ID_2;
		$sut->noteID = self::NOTE_ID_2;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertSame ( '2', $sut->itemID );
		$this->assertSame ( '1', $sut->noteID );
	}
	public function testUpdateItemNotesSuccess(): void {
		$sut = $this->createSutWithId ( $this->getValidId () );
		$sut->itemID = self::ITEM_ID_2;
		$sut->noteID = self::NOTE_ID_15;
		$this->assertTrue ( $sut->update () );
		try {
			$sut->get ();
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->assertSame ( '2', $sut->itemID );
		$this->assertSame ( '15', $sut->noteID );
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteItemNotesItemNotesIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteItemNotesItemNotesIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
		$sut->delete ();
	}
	public function testDeleteItemNotesItemNotesIdValid(): void {
		$sut = $this->createSutWithId ( self::ITEM_NOTE_ID_2 );
		try {
			$this->assertTrue ( $sut->delete () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * exists(): bool
	 */
	public function testExistsItemNotesItemNotesIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsItemNotesItemNotesIdInvalid(): void {
		$sut = $this->createSutWithId ( $this->getInvalidId () );
		$this->assertFalse ( $sut->exists () );
	}
	public function testExistsItemNotesItemNotesIdValid(): void {
		$sut = $this->createSutWithId ( self::NOTE_ID_2 );
		$this->assertTrue ( $sut->exists () );
	}
	
	/*
	 * existsNoteID(): bool
	 */
	public function testExistsItemNotesNoteIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->existsNoteID () );
	}
	public function testExistsItemNotesNoteIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->noteID = $this->getInvalidId ();
		$this->assertFalse ( $sut->existsNoteID () );
	}
	public function testExistsItemNotesNoteIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->noteID = $this->getValidId ();
		$this->assertTrue ( $sut->existsNoteID () );
	}
	
	/*
	 * existsItemID(): bool
	 */
	public function testExistsItemNotesItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->assertFalse ( $sut->existsItemID () );
	}
	public function testExistsItemNotesItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->assertFalse ( $sut->existsItemID () );
	}
	public function testExistsItemNotesItemIdValid(): void {
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
	 * getItemNotes(): array
	 */
	public function testGetItemNotesItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->getItemNotes ();
	}
	public function testGetItemNotesItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->getItemNotes ();
	}
	public function testGetItemNotesItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_2;
		try {
			$i = 6;
			foreach ( $sut->getItemNotes () as $obj ) {
				$this->assertEquals ( $i, $obj->item_noteID );
				$this->assertEquals ( 2, $obj->itemID );
				$this->assertEquals ( $i, $obj->noteID );
				$i ++;
			}
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * getItemNote(): ItemNotes
	 */
	public function testGetItemNoteNoteIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->getItemNote ();
	}
	public function testGetItemNoteNoteIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->noteID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->getItemNote ();
	}
	public function testGetItemNoteNoteIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->noteID = self::NOTE_ID_2;
		try {
			$obj = $sut->getItemNote ();
			$this->assertEquals ( 2, $obj->item_noteID );
			$this->assertEquals ( 1, $obj->itemID );
			$this->assertEquals ( 2, $obj->noteID );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
	}
	
	/*
	 * deleteNote(): bool
	 */
	public function testDeleteItemNoteNoteIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->deleteItemNote ();
	}
	public function testDeleteItemNoteNoteIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->noteID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_NOTE_ID_NOT_EXIST );
		$sut->deleteItemNote ();
	}
	public function testDeleteItemNoteNoteIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->noteID = self::NOTE_ID_2;
		try {
			$this->assertTrue ( $sut->deleteItemNote () );
		} catch ( ModelException $e ) {
		}
		$sut = $this->createSutWithId ( self::ITEM_NOTE_ID_2 );
		$this->expectExceptionMessage ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
		$sut->get ();
	}
	
	/*
	 * deleteItemNotes(): bool
	 */
	public function testDeleteItemNotesItemIdEmpty(): void {
		$sut = $this->createDefaultSut ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->deleteItemNotes ();
	}
	public function testDeleteItemNotesItemIdInvalid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = $this->getInvalidId ();
		$this->expectExceptionMessage ( self::ERROR_ITEM_ID_NOT_EXIST );
		$sut->deleteItemNotes ();
	}
	public function testDeleteItemNotesItemIdValid(): void {
		$sut = $this->createDefaultSut ();
		$sut->itemID = self::ITEM_ID_2;
		try {
			$this->assertTrue ( $sut->deleteItemNotes () );
		} catch ( ModelException $e ) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		for($i = 6; $i <= 10; $i ++) {
			$sut = $this->createSutWithId ( $i );
			$this->expectExceptionMessage ( self::ERROR_ITEM_NOTE_ID_NOT_EXIST );
			$sut->get ();
		}
	}
} 
