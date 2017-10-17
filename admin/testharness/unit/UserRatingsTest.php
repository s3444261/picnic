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
 * -- UserRatings.php Test Blocks: --
 * get(): UserRatings
 * set(): int
 * update(): bool
 * delete(): bool
 * deleteItemId(): bool
 * deleteUserId(): bool
 * exists(): bool
 * addSellerRating(): UserRatings
 * addBuyerRating(): bool
 *
 * -- UserRatings.php Test SubBlocks: --
 * get(): UserRatings
 * -- testGetUserRatingsNoUserRatingsId(): void
 * -- testGetUserRatingsInvalidUserRatingsId(): void
 * -- testGetUserRatingsValidUserRatingsId(): 
 * 
 * set(): int
 * -- testSetUserRatingsSuccess(): void
 * 
 * update(): bool
 * -- testUpdateUserRatingsNoUserRatingsId(): void
 * -- testUpdateUserRatingsInvalidUserRatingsId(): void
 * -- testUpdateUserRatingsEmptyUserRatings(): void
 * -- testUpdateUserRatingsSuccess(): void
 * 
 * delete(): bool
 * -- testDeleteUserRatingsUserRatingsIdEmpty(): void
 * -- testDeleteUserRatingsUserRatingsIdInvalid(): void
 * -- testDeleteUserRatingsUserRatingsIdValid(): void
 * 
 * deleteItemId(): bool
 * -- testDeleteUserRatingsItemIdEmpty(): void
 * -- testDeleteUserRatingsItemIdInvalid(): void
 * -- testDeleteUserRatingsItemIdValid(): void
 * 
 * deleteUserId(): bool
 * -- testDeleteUserRatingsUserIdEmpty(): void
 * -- testDeleteUserRatingsUserIdInvalid(): void
 * -- testDeleteUserRatingsUserIdValid(): void
 * 
 * exists(): bool
 * -- testExistsUserRatingsUserRatingsIdEmpty(): void
 * -- testExistsUserRatingsUserRatingsIdInvalid(): void
 * -- testExistsUserRatingsUserRatingsIdValid(): void
 * 
 * addSellerRating(): UserRatings
 * -- testAddSellerRatingUserIdInvalid(): void
 * -- testAddSellerRatingItemIdInvalid(): void
 * -- testAddSellerRatingRatingNotSet(): void
 * -- testAddSellerRatingSuccess(): void
 * 
 * addBuyerRating(): bool
 * -- testAddBuyerRatingTransactionIdEmpty(): void
 * -- testAddBuyerRatingTransactionIdInvalid(): void
 * -- testAddBuyerRatingRatingInvalid(): void
 * -- testAddBuyerRatingSuccess(): void
 */

declare(strict_types=1);

require_once 'TestPDO.php';
require_once 'PicnicTestCase.php';
require_once dirname(__FILE__) . '/../../createDB/DatabaseGenerator.php';
require_once dirname(__FILE__) . '/../../../model/UserRatings.php';
require_once dirname(__FILE__) . '/../../../model/User.php';
require_once dirname(__FILE__) . '/../../../model/Item.php';
require_once dirname(__FILE__) . '/../../../model/UserItems.php';
require_once dirname(__FILE__) . '/../../../model/Validation.php';
require_once dirname(__FILE__) . '/../../../model/ModelException.php';
require_once dirname(__FILE__) . '/../../../model/ValidationException.php';

final class UserRatingsTest extends PicnicTestCase{

	const USER_RATING_ID   = 'user_ratingID';
	const ITEM_ID = 'itemID';
	const SELLRATING = 'sellrating';
	const USER_ID = 'userID';
	const BUYRATING = 'buyrating';
	const TRANSACTION = 'transaction';
	const CREATION_DATE = 'created_at';
	const MODIFIED_DATE = 'updated_at';
	
	const USER 			= 'user';
	const EMAIL         = 'email';
	const PASSWORD 		= 'password';
	
	const TITLE 			= 'title';
	
	const USER_ID_1 = '1';
	const USER_1 = 'peter';
	const EMAIL_1 = 'peter@gmail.com';
	const PASSWORD_1 = 'TestTest88';
	const USER_ID_2 = '2';
	const USER_2 = 'mary';
	const EMAIL_2 = 'mary@gmail.com';
	const PASSWORD_2 = 'TestTest77';
	
	const ITEM_ID_1         = 1;
	const TITLE_1			= 'title1';
	const ITEM_ID_2         = 2;
	const TITLE_2			= 'title2';
	
	const USER_RATING_ID_1   = 1;
	const SELLRATING_1 = 3;
	const BUYRATING_1 = 4;
	
	const USER_RATING_ID_2   = 2;
	const SELLRATING_2 = 4;
	const USER_ID_3 = 3;
	const BUYRATING_2 = 3;
	
	const ERROR_USER_RATING_ID_NOT_EXIST = 'The UserRatingID does not exist!';
	const ERROR_USER_ID_NOT_EXIST = 'The UserID does not exist!';
	const ERROR_ITEM_ID_NOT_EXIST = 'The ItemID does not exist!';
	const ERROR_RATING_NOT_SET = 'The rating has not been set!';
	const ERROR_INCORRECT_TRANSACTION_ID = 'The TransactionID is incorrect!';
	const ERROR_USER_ID_NOT_INT = 'UserID must be an integer!';

	protected function setUp(): void {
		// Regenerate a fresh database.
		TestPDO::CreateTestDatabaseAndUser();
		$pdo = TestPDO::getInstance();
		DatabaseGenerator::Generate($pdo);
		
		$u1 = new User($pdo, [self::USER => self::USER_1, self::EMAIL => self::EMAIL_1, self::PASSWORD => self::PASSWORD_1]);
		$u2 = new User($pdo, [self::USER => self::USER_2, self::EMAIL => self::EMAIL_2, self::PASSWORD => self::PASSWORD_2]);
		$i1 = new Item($pdo, [self::TITLE =>self::TITLE_1]);
		$i2 = new Item($pdo, [self::TITLE =>self::TITLE_2]);
		$ui = new UserItems($pdo, [self::USER_ID => self::USER_ID_1, self::ITEM_ID => self::ITEM_ID_1]);
		$ui = new UserItems($pdo, [self::USER_ID => self::USER_ID_1, self::ITEM_ID => self::ITEM_ID_2]);
		$ur = new UserRatings($pdo, [self::ITEM_ID => self::ITEM_ID_1, self::SELLRATING => self::SELLRATING_1, self::USER_ID => self::USER_ID_1, self::BUYRATING => self::BUYRATING_1]);
		
		try {
			$u1->set();
			$u2->set();
			$i1->set();
			$i2->set();
			$ui->set();
			$ur->set();
		} catch (ModelException $e) {}
	}
	
	protected function addSellerRating(): UserRatings {
		$sut = $this->createDefaultSut();
		$sut->userID = self::USER_ID_2;
		$sut->itemID = self::ITEM_ID_2;
		$sut->sellrating = self::SELLRATING_2;
		try {
			$sut->addSellerRating();
		} catch (ModelException $e) {}
		$sut = $this->createSutWithId(self::USER_RATING_ID_2);
		try {
			return $sut->get();
		} catch (ModelException $e) {}
	}

	protected function tearDown(): void {
		TestPDO::CleanUp();
	}

	protected function createDefaultSut(){
		return new UserRatings(TestPDO::getInstance());
	}

	protected function createSutWithId($id){
		return new UserRatings(TestPDO::getInstance(), [self::USER_RATING_ID => $id]);
	}

	protected function getValidId() {
		return 1;
	}

	protected function getInvalidId() {
		return 2000;
	}

	protected function getExpectedAttributesForGet() {

		return [
			self::ITEM_ID => self::ITEM_ID_1, 
			self::SELLRATING => self::SELLRATING_1, 
			self::USER_ID => self::USER_ID_1, 
			self::BUYRATING => self::BUYRATING_1
		];
	}

	public function testAttributes(): void {
		$values = [
			self::ITEM_ID => self::ITEM_ID_2,
			self::SELLRATING => self::SELLRATING_2,
			self::USER_ID => self::USER_ID_2,
			self::BUYRATING => self::BUYRATING_2,
			self::CREATION_DATE => '1984-08-18',
			self::MODIFIED_DATE => '2015-02-13'
		];

		$this->assertAttributesAreSetAndRetrievedCorrectly($values);
	}

	/*
	 * get(): UserRatings
	 */	
	public function testGetUserRatingsNoUserRatingsId(): void {
		$sut = $this->createDefaultSut();
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->get();
	}
	
	public function testGetUserRatingsInvalidUserRatingsId(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->get();
	}
	
	public function testGetUserRatingsValidUserRatingsId(): void {
		$sut = $this->createSutWithId(self::USER_RATING_ID_1);
		try {
			$sut->get();
		} catch (ModelException $e) {}
		$this->assertEquals(self::USER_RATING_ID_1, $sut->user_ratingID);
		$this->assertEquals(self::ITEM_ID_1, $sut->itemID);
		$this->assertEquals(self::SELLRATING_1, $sut->sellrating);
		$this->assertEquals(self::USER_ID_1, $sut->userID);
		$this->assertEquals(self::BUYRATING_1, $sut->buyrating);
	}
	
	/*
	 * set(): int
	 */
	public function testSetUserRatingsSuccess(): void {
		$sut = $this->createDefaultSut();
		$sut->itemID = self::ITEM_ID_2;
		$sut->sellrating = self::SELLRATING_2;
		$sut->userID = self::USER_ID_2;
		$sut->buyrating = self::BUYRATING_2;
		try {
			$sut->user_ratingID = $sut->set(); 
		} catch (ModelException $e) {}
		$sut = $this->createSutWithId(self::USER_RATING_ID_2); 
		try {
			$sut->get();
			$this->assertEquals(self::USER_RATING_ID_2, $sut->user_ratingID);
			$this->assertEquals(self::ITEM_ID_2, $sut->itemID);
			$this->assertEquals(self::SELLRATING_2, $sut->sellrating);
			$this->assertEquals(self::USER_ID_2, $sut->userID);
			$this->assertEquals(self::BUYRATING_2, $sut->buyrating);
		} catch (ModelException $e) {}
	}
	
	/*
	 * update(): bool
	 */
	public function testUpdateUserRatingsNoUserRatingsId(): void {
		$sut = $this->createDefaultSut();
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->update();
	}
	
	public function testUpdateUserRatingsInvalidUserRatingsId(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->update();
	}
	
	public function testUpdateUserRatingsNoUserRatings(): void {
		$sut = $this->createSutWithId(self::USER_RATING_ID_1);
		$this->assertTrue($sut->update());
	}
	
	public function testUpdateUserRatingsSuccess(): void {
		$sut = $this->createSutWithId(self::USER_RATING_ID_1);
		$sut->itemID = self::ITEM_ID_2;
		$sut->sellrating = self::SELLRATING_2;
		$sut->userID = self::USER_ID_2;
		$sut->buyrating = self::BUYRATING_2;
		$this->assertTrue($sut->update());
		$this->assertEquals(self::USER_RATING_ID_1, $sut->user_ratingID);
		$this->assertEquals(self::ITEM_ID_2, $sut->itemID);
		$this->assertEquals(self::SELLRATING_2, $sut->sellrating);
		$this->assertEquals(self::USER_ID_2, $sut->userID);
		$this->assertEquals(self::BUYRATING_2, $sut->buyrating);
	}
	
	/*
	 * delete(): bool
	 */
	public function testDeleteUserRatingsUserRatingsIdEmpty(): void {
		$sut = $this->createDefaultSut();
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->delete();
	}
	
	public function testDeleteUserRatingsUserRatingsIdInvalid(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->delete();
	}
	
	public function testDeleteUserRatingsUserRatingsIdValid(): void {
		$sut = $this->createSutWithId(self::USER_RATING_ID_1);
		$this->assertTrue($sut->delete());
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->get();
	}
	
	/*
	 * deleteItemId(): bool
	 */
	public function testDeleteItemIdItemIdEmpty(): void {
		$sut = $this->createDefaultSut();
		$this->expectExceptionMessage(self::ERROR_ITEM_ID_NOT_EXIST);
		$sut->deleteItemId();
	}
	
	public function testDeleteItemIdItemIdInvalid(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->expectExceptionMessage(self::ERROR_ITEM_ID_NOT_EXIST);
		$sut->deleteItemId();
	}
	
	public function testDeleteItemIdItemIdValid(): void {
		$sut = $this->createDefaultSut();
		$sut->itemID = self::ITEM_ID_1;
		$this->assertTrue($sut->deleteItemId());
		$sut = $this->createSutWithId(self::USER_RATING_ID_1);
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->get();
	}
	
	/*
	 * deleteUserId(): bool
	 */
	public function testDeleteUserIdUserIdEmpty(): void {
		$sut = $this->createDefaultSut();
		$this->expectExceptionMessage(self::ERROR_USER_ID_NOT_INT);
		$sut->deleteUserId();
	}
	
	public function testDeleteUserIdUserIdInvalid(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->expectExceptionMessage(self::ERROR_USER_ID_NOT_INT);
		$sut->deleteUserId();
	}
	
	public function testDeleteUserIdUserIdValid(): void {
		$sut = $this->createDefaultSut();
		$sut->userID = self::USER_ID_1;
		$this->assertTrue($sut->deleteUserId());
		$sut = $this->createSutWithId(self::USER_RATING_ID_1);
		$this->expectExceptionMessage(self::ERROR_USER_RATING_ID_NOT_EXIST);
		$sut->get();
	}
	 
	/*
	 * exists(): bool
	 */
	public function testExistsUserRatingsUserRatingsIdEmpty(): void {
		$sut = $this->createDefaultSut();
		$this->assertFalse($sut->exists());
	}
	
	public function testExistsUserRatingsUserRatingsIdInvalid(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->assertFalse($sut->exists());
	}
	
	public function testExistsUserRatingsUserRatingsIdValid(): void {
		$sut = $this->createSutWithId(self::USER_RATING_ID_1);
		$this->assertTrue($sut->exists());
	}
	
	/*
	 * addSellerRating(): UserRatings
	 */
	
	public function testAddSellerRatingUserIdInvalid(): void {
		$sut = $this->createDefaultSut();
		$sut->userID = $this->getInvalidId();
		$sut->itemID = self::ITEM_ID_2;
		$sut->sellrating = self::SELLRATING_2;
		$this->expectExceptionMessage(self::ERROR_USER_ID_NOT_EXIST);
		$sut->addSellerRating();
	}
	
	public function testAddSellerRatingItemIdInvalid(): void {
		$sut = $this->createDefaultSut();
		$sut->userID = self::USER_ID_2;
		$sut->itemID = $this->getInvalidId();
		$sut->sellrating = self::SELLRATING_2;
		$this->expectExceptionMessage(self::ERROR_ITEM_ID_NOT_EXIST);
		$sut->addSellerRating();
	}
	
	public function testAddSellerRatingRatingNotSet(): void {
		$sut = $this->createDefaultSut();
		$sut->userID = self::USER_ID_2;
		$sut->itemID = self::ITEM_ID_2;
		$this->expectExceptionMessage(self::ERROR_RATING_NOT_SET);
		$sut->addSellerRating();
	}
	
	public function testAddSellerRatingSuccess(): void {
		$sut = $this->createDefaultSut();
		$sut->userID = self::USER_ID_2;
		$sut->itemID = self::ITEM_ID_2;
		$sut->sellrating = self::SELLRATING_2;
		try {
			$sut->addSellerRating();
		} catch (ModelException $e) {}
		$sut = $this->createSutWithId(self::USER_RATING_ID_2);
		try {
			$sut->get();
		} catch (ModelException $e) {}
		$this->assertEquals(self::USER_RATING_ID_2, $sut->user_ratingID);
		$this->assertEquals(self::ITEM_ID_2, $sut->itemID);
		$this->assertEquals(self::SELLRATING_2, $sut->sellrating);
		$this->assertEquals(self::USER_ID_2, $sut->userID);
	}
	
	/*
	 * addBuyerRating(): bool
	 */
	public function testAddBuyerRatingTransactionIdEmpty(): void {
		$sut = $this->addSellerRating();
		$sut->buyrating = self::BUYRATING_2; 
		$sut->transaction = '';
		$this->expectExceptionMessage(self::ERROR_INCORRECT_TRANSACTION_ID);
		$sut->addBuyerRating();
	}
	
	public function testAddBuyerRatingTransactionIdInvalid(): void {
		$sut = $this->addSellerRating();
		$sut->transaction = $this->getInvalidId();
		$sut->buyrating = self::BUYRATING_2;
		$this->expectExceptionMessage(self::ERROR_INCORRECT_TRANSACTION_ID);
		$sut->addBuyerRating();
	}
	
	public function testAddBuyerRatingRatingInvalid(): void {
		$sut = $this->addSellerRating();
		$sut->buyrating = $this->getInvalidId();
		$this->expectExceptionMessage(self::ERROR_RATING_NOT_SET);
		$sut->addBuyerRating();
	}
	
	public function testAddBuyerRatingSuccess(): void {
		$sut = $this->addSellerRating();
		$sut->buyrating = self::BUYRATING_2;
		try {
			$sut->addBuyerRating();
			$sut->get();
		} catch (Exception $e) {}
		$this->assertEquals(self::BUYRATING_2, $sut->buyrating);
		$this->assertNull($sut->transaction);
	}

}
