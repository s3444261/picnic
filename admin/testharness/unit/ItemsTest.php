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
 * -- Items.php Test Blocks: --
 * search(string $searchString): array
 * searchArray(array $searchArray): array
 * searchAdvanced(array $args): array
 *
 * -- Items.php Test SubBlocks: --
 * search(string $searchString): array
 * -- testSearchTitleNegativeResult(): void
 * -- testSearchDescriptionNegativeResult(): void
 * -- testSearchPositiveResult(): void
 *
 * searchArray(array $searchArray): array
 *
 * searchAdvanced(array $args): array
 */
declare ( strict_types = 1 )
	;

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname ( __FILE__ ) . '/../../createDB/DatabaseGenerator.php';
require_once dirname ( __FILE__ ) . '/../../../model/Category.php';
require_once dirname ( __FILE__ ) . '/../../../model/CategoryItems.php';
require_once dirname ( __FILE__ ) . '/../../../model/Item.php';
require_once dirname ( __FILE__ ) . '/../../../model/Items.php';
require_once dirname ( __FILE__ ) . '/../../../model/User.php';
require_once dirname ( __FILE__ ) . '/../../../model/Validation.php';
require_once dirname ( __FILE__ ) . '/../../../model/ValidationException.php';

class ItemsTest extends PHPUnit\Framework\TestCase{
	
	const STRING_NOT_EXIST = 'Hammer';
	const TITLE_EXIST = 'Word3 word4';
	const DESCRIPTION_EXIST = 'word7 word8';
	const AND_WORD = '+word12';
	const NOT_WORD = '-word12';
	const OR_WORD = 'word12';
	const AND_WORD_AND_WORD = '+word12 +word13';
	const NOT_WORD_AND_WORD = '-word12 +word13';
	const OR_WORD_AND_WORD = 'word12 +word13';
	const AND_WORD_NOT_WORD = '+word12 -word13';
	const NOT_WORD_NOT_WORD = '-word12 -word13';
	const OR_WORD_NOT_WORD = 'word12 -word13';
	const AND_WORD_OR_WORD = '+word12 word13';
	const NOT_WORD_OR_WORD = '-word12 word13';
	const OR_WORD_OR_WORD = 'word12 word13';
	const AND_WORD_AND_WORD_AND_WORD = '+word12 +word13 +word14';
	const AND_WORD_TILDA_WORD = '+word12 ~word13'; 
	const AND_WORD_AND_GREATER_WORD_LESS_WORD = 'word12 +(>word13 <word14)';
	
	/**
	 * Sets up the initial database for each test.
	 * 
	 * {@inheritDoc}
	 * @see \PHPUnit\Framework\TestCase::setUp()
	 */
	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser ();
		$pdo = TestPDO::getInstance ();
		DatabaseGenerator::Generate ( $pdo );

		// Populate the Category Table
		$j = 1;
		for($i = 1; $i <= 101; $i ++) {
			if($i == 1){
				$c = new Category ( $pdo );
				$c->category = 'category1';
				try {
					$c->set ();
				} catch (ModelException $e) {
					$this->assertEquals('Exception', $e->getMessage());
				}
				
			} else {
				$c = new Category ( $pdo );
				$c->parentID = $j;
				$c->category = 'category' . $i;
				try {
					$c->set ();
				} catch (ModelException $e) {
					$this->assertEquals('Exception', $e->getMessage());
				}
				
				if ($i % 3 == 0) {
					$j ++;
				}
			}
		}
		
		// Populate User Table
		
		$user = new User ( $pdo, [
				'user' => 'user',
				'email' => 'user@gmail.com',
				'password' => 'TestTest88'
		] );
		
		try {
			$user->userID = $user->set ();
		} catch (ModelException $e) {
			$this->assertEquals('Exception', $e->getMessage());
		}
		
		// Populate the Item and Category_items Tables
		
		for($i = 2; $i <= 101; $i ++) {
			for($j = 1; $j <= 5; $j++){
				$item = new Item($pdo);
				$item->owningUserID = 1;
				$item->title = 'Word' . $i . ' word' . ($i + 1) . ' word' . ($i + 2) . ' word' . ($i + 3);
				$item->description = 'Word' . ($i + 4) . ' word' . ($i + 5) . ' word' . ($i + 6) . ' word' . ($i + 7);
				$item->price = $i . '.' . $j . '0';
				$item->quantity = ceil($i/$j);
				if($i % 2 == 0){
					$item->itemcondition = 'New';
				} else {
					$item->itemcondition = 'Used';
				}
				if($i % 3 == 0){
					$item->status = 'ForSale';
				} else {
					$item->status = 'Wanted';
				} 
				try {
					$item->itemID= $item->set();
				} catch (ModelException $e) {
					$this->assertEquals('Exception', $e->getMessage());
				}
			}
		}
	}
	
	/**
	 * Clears the database after each test
	 * 
	 * {@inheritDoc}
	 * @see \PHPUnit\Framework\TestCase::tearDown()
	 */
	protected function tearDown(): void {
		//TestPDO::CleanUp ();
	}
	
	/**
	 * Creates a default instance of the Items Class
	 * 
	 * {@inheritDoc}
	 * @see PicnicTestCase::createDefaultSut()
	 */
	protected function createDefaultSut() {
		return new Items ( TestPDO::getInstance () );
	}
	
	/*
	 * search(string $searchString): array
	 */
	
	/**
	 * Does not return anything.
	 */
	public function testSearchNegativeResult(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->search(self::STRING_NOT_EXIST);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns anything from either title or description that has
	 * either Word3 or word4.  Case insensitive.
	 */
	public function testSearchTitlePositiveResult(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->search(self::TITLE_EXIST);
		$this->assertEquals(15, count($array));
	}
	
	/**
	 * Returns anything from either title or description that has
	 * either Word7 or word8.  Case insensitive.
	 */
	public function testSearchDescriptionPositiveResult(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->search(self::DESCRIPTION_EXIST);
		$this->assertEquals(35, count($array));
	}
	
	/*
	 * searchArray(array $searchArray): array
	 */
	/**
	 * Returns nothing as string does not exist.
	 */
	public function testSearchArrayTitleNegativeResult(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::STRING_NOT_EXIST);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 40 rows.  Each row has word12 (case insensitive)
	 * somewhere in either the title or description.
	 */
	public function testSearchArrayAndWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::AND_WORD);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * This does not return any rows as the actual result contains
	 * more than half the database.
	 */
	public function testSearchArrayNotWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::NOT_WORD);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 40 rows.  Each row has word12 (case insensitive)
	 * somewhere in either the title or description.
	 */
	public function testSearchArrayOrWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::OR_WORD);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 35 rows.  Each row has both word12 (case insensitive)
	 * and word 13 somewhere in either the title or description.
	 */
	public function testSearchArrayAndWordAndWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::AND_WORD_AND_WORD);
		$this->assertEquals(35, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain Word13 only.
	 */
	public function testSearchArrayNotWordAndWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::NOT_WORD_AND_WORD);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 40 Rows. Each row has either both word12 and
	 * word13 in either the title or description, or has just
	 * word13 in either the title or description.
	 */
	public function testSearchArrayOrWordAndWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::OR_WORD_AND_WORD);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain word12 only.
	 */
	public function testSearchArrayAndWordNotWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::AND_WORD_NOT_WORD);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 0 rows as in actual fact more than half the
	 * database would be returned in this instance.
	 */
	public function testSearchArrayNotWordNotWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::NOT_WORD_NOT_WORD);
		$this->assertEquals(0, count($array));
	}
	
	/**
	 * Returns 5 Rows.  These rows contain word12 only.
	 */
	public function testSearchArrayOrWordNotWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::OR_WORD_NOT_WORD);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 40 Rows.  All rows contain word12.  35 rows
	 * contain word12 and word13.  5 rows contain only
	 * word12.  The rows that contain only word12 were expected
	 * to rate lower than those rows that also contain word13, 
	 * however the opposite seems to be the case.
	 */
	public function testSearchArrayAndWordOrWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::AND_WORD_OR_WORD);
		$this->assertEquals(40, count($array));
	}
	
	/**
	 * Returns 5 rows.  These rows only contain word13.
	 */
	public function testSearchArrayNotWordOrWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::NOT_WORD_OR_WORD);
		$this->assertEquals(5, count($array));
	}
	
	/**
	 * Returns 45 Rows.  These rows contain either word12 only,
	 * word13 only, or both word12 and word13.
	 */
	public function testSearchArrayOrWordOrWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::OR_WORD_OR_WORD);
		$this->assertEquals(45, count($array));
	}
	
	/**
	 * Returns 30 Rows. Each row contains all word12, word13 and
	 * word14 across the title and description tables. 
	 */
	public function testSearchArrayAndWordAndWordAndWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::AND_WORD_AND_WORD_AND_WORD);
		$this->assertEquals(30, count($array));
	}
	
	/**
	 * Returns 40 rows.  The first 5 rows contain word12 only and rank
	 * higher than the rows that also contain word13.
	 */
	public function testSearchArrayAndWordTildaWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::AND_WORD_TILDA_WORD);
		$this->assertEquals(40, count($array));
		$this->assertEquals(16, $array[0]->itemID);
		$this->assertEquals(17, $array[1]->itemID);
		$this->assertEquals(18, $array[2]->itemID);
		$this->assertEquals(19, $array[3]->itemID);
		$this->assertEquals(20, $array[4]->itemID);
	}
	
	/**
	 * Returns 45 rows.  Rows that don't contain word14 rank higher than those that do.
	 */
	public function testSearchArrayAndWordAndGreaterWordLessWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::AND_WORD_AND_GREATER_WORD_LESS_WORD);
		$this->assertEquals(45, count($array));
		$this->assertEquals(21, $array[0]->itemID);
		$this->assertEquals(22, $array[1]->itemID);
		$this->assertEquals(23, $array[2]->itemID);
		$this->assertEquals(24, $array[3]->itemID);
		$this->assertEquals(25, $array[4]->itemID);
	}
}
