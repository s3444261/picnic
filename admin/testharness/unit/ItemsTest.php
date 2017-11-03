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
	const TITLE_EXIST = 'Title 50 and title4';
	const DESCRIPTION_EXIST = 'about Title51 and';
	const WORD_AND_WORD = '+Title2 +title4';
	const WORD_OR_WORD = 'Title2 title4';
	const WORD_AND_WORD_AND_WORD = 'Title2 +title4 +plusThis';
	const WORD_AND_WORD_OR_WORD = 'Title2 +title4 plusThis';
	const WORD_AND_WORD_NOT_WORD = 'Title2 +title4 -plusThis';
	
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
				$item->title = 'Title' . $i . ' title' . $j;
				if($i % 4 == 0){
					$item->title = $item->title . ' plusThis';
				}
				if($i % 8 == 0){
					$item->title = $item->title . ' butNotThis';
				}
				$item->description = 'A little bit to say about Title ' . 
										$i . ' and title' . $j;
				if($i % 5 == 0){
					$item->description = $item->description . ' maybeThis';
				}
				if($i % 10 == 0){
					$item->description = $item->description . ' maybeNotThis';
				}
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
	public function testSearchNegativeResult(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->search(self::STRING_NOT_EXIST);
		$this->assertEquals(0, count($array));
	}
	
	public function testSearchTitlePositiveResult(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->search(self::TITLE_EXIST);
		$this->assertEquals(104, count($array));
	}
	
	public function testSearchDescriptionPositiveResult(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->search(self::DESCRIPTION_EXIST);
		$this->assertEquals(5, count($array));
	}
	
	/*
	 * searchArray(array $searchArray): array
	 */
/*	public function testSearchArrayTitleNegativeResult(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::STRING_NOT_EXIST);
		$this->assertEquals(0, count($array));
	}
	
	public function testSearchArrayWordAndWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::WORD_AND_WORD);
		$this->assertEquals(2, count($array));
	}
	
	public function testSearchArrayWordOrWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::WORD_OR_WORD);
		$this->assertEquals(206, count($array));
	}
	
	public function testSearchArrayWordAndWordAndWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::WORD_AND_WORD_AND_WORD);
		$this->assertEquals(1, count($array));
	}
	
	public function testSearchArrayWordAndWordOrWord(): void {
		$sut = $this->createDefaultSut();
		$array = $sut->searchArray(self::WORD_AND_WORD_OR_WORD);
		$this->assertEquals(1, count($array));
	} */
}
