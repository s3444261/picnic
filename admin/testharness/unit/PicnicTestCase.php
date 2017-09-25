<?php
/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

// declare(strict_types=1);

/**
 * A base test for tests, providing commonly used methods.
 */
abstract class PicnicTestCase extends PHPUnit\Framework\TestCase {

	/**
	 * Creates a new default test object locally.
	 */
	abstract protected function createDefaultSut();

	/**
	 * Creates a new default test object locally, initialized with the given ID.
	 *
	 * @param $id		The ID to be assigned to the object.
	 */
	abstract protected function createSutWithId($id);

	/**
	 * Gets an ID of an object that should be valid for the test.
	 */
	abstract protected function getValidId();

	/**
	 * Gets an ID of an object that should NOT be valid for the test.
	 */
	abstract protected function getInvalidId();

	/**
	 * Gets the type of exception that should be thrown on an error.
	 */
	abstract protected function getExpectedExceptionTypeForUnsetId();

	/**
	 * Gets the attributes that we expect to be returned when we get a valid object.
	 */
	abstract protected function getExpectedAttributesForGet();

	/**
	 * Sets attributes on $sut, using the values passed in the associative
	 * array $desiredValues.
	 *
	 * @param $sut   			The object on which to set attributes.
	 * @param $desiredValues	An associative array of attribute names vs values.
	 */
	protected function setAttributes($sut, $desiredValues): void {
		foreach ($desiredValues as $x=>$x_value) {
			$sut->$x = $x_value;
		}
	}

	/**
	 * Verifies that $sut has attributes set as specified in the associative
	 * array $expectedValues.
	 *
	 * @param $sut   			The object whose attributes will be tested.
	 * @param $expectedValues	An associative array of attribute names vs values.
	 */
	protected function assertValuesAreEqualTo($sut, $expectedValues): void {
		foreach ($expectedValues as $x=>$x_value) {
			$this->assertEquals($x_value, $sut->$x);
		}
	}

	protected function assertAttributesAreSetAndRetrievedCorrectly($values): void {
		$sut = $this->createDefaultSut();
		$this->setAttributes($sut, $values);
		$this->assertValuesAreEqualTo($sut, $values);
	}

	public function testGetReturnsSelfOrTrueForKnownId(): void {
		$sut = $this->createSutWithId($this->getValidId());

		$result = $sut->get();

		if ($result == true) {
			$this->addToAssertionCount(1);
		} else {
			$this->assertEquals($sut, $result);
		}
	}

	public function testGetThrowsOrReturnsFalseForUnknownId(): void {
		$exceptionType = $this->getExpectedExceptionTypeForUnsetId();

		if ($exceptionType != null) {
			$sut = $this->createSutWithId($this->getInvalidId());
			$this->expectException($exceptionType);
			$sut->get();
		} else {
			$sut = $this->createSutWithId($this->getInvalidId());
			$this->assertFalse($sut->get());
		}
	}

	public function testGetRetrievesCorrectValuesForKnownId(): void {
		$sut = $this->createSutWithId($this->getValidId());
		$sut->get();
		$this->assertValuesAreEqualTo($sut, $this->getExpectedAttributesForGet());
	}

	public function testGetThrowsOrReturnsFalseForUnsetId(): void {

		$exceptionType = $this->getExpectedExceptionTypeForUnsetId();

		if ($exceptionType != null) {
			$sut = $this->createDefaultSut();
			$this->expectException($exceptionType);
			$sut->get();
		} else {
			$sut = $this->createDefaultSut();
			$this->assertFalse($sut->get());
		}
	}

	public function testExistsReturnsTrueForKnownId(): void {
		$sut = $this->createSutWithId($this->getValidId());
		$this->assertTrue($sut->exists());
	}

	public function testExistsReturnsFalseForUnknownId(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->assertFalse($sut->exists());
	}

	public function testExistsReturnsFalseForUnsetId(): void {
		$sut = $this->createDefaultSut();
		$this->assertFalse($sut->exists());
	}

	public function testDeleteReturnsTrueForKnownId(): void {
		$sut = $this->createSutWithId($this->getValidId());
		$this->assertTrue($sut->delete());
	}

	public function testDeleteReturnsFalseForUnknownId(): void {
		$sut = $this->createSutWithId($this->getInvalidId());
		$this->assertFalse($sut->delete());
	}

	public function testDeleteReturnsFalseForUnsetId(): void {
		$sut = $this->createDefaultSut();
		$this->assertFalse($sut->delete());
	}
}
