<?php
/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

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

	protected function assertGetIsFunctional($validId, $invalidId, $expectedValuesForValidId): void {
		$this->assertGetReturnsTrueForKnownId($validId);
		$this->assertGetReturnsFalseForUnknownId($invalidId);
		$this->assertGetReturnsFalseForUnsetId();
		$this->assertGetRetrievesCorrectValuesForKnownId($validId, $expectedValuesForValidId);
	}

	private function assertGetReturnsTrueForKnownId($validId): void {
		$sut = $this->createSutWithId($validId);
		$this->assertTrue($sut->get());
	}

	private function assertGetReturnsFalseForUnknownId($invalidId): void {
		$sut = $this->createSutWithId($invalidId);
		$this->assertFalse($sut->get());
	}

	private function assertGetRetrievesCorrectValuesForKnownId($validId, $expectedValues): void {
		$sut = $this->createSutWithId($validId);
		$sut->get();
		$this->assertValuesAreEqualTo($sut, $expectedValues);
	}

	private function assertGetReturnsFalseForUnsetId(): void {
		$sut = $this->createDefaultSut();
		$this->assertFalse($sut->get());
	}

	protected function assertExistsIsFunctional($validId, $invalidId): void {
		$this->assertExistsReturnsTrueForKnownId($validId);
		$this->assertExistsReturnsFalseForUnknownId($invalidId);
		$this->assertExistsReturnsFalseForUnsetId();
	}

	private function assertExistsReturnsTrueForKnownId($validId): void {
		$sut = $this->createSutWithId($validId);
		$this->assertTrue($sut->exists());
	}

	private function assertExistsReturnsFalseForUnknownId($invalidId): void {
		$sut = $this->createSutWithId($invalidId);
		$this->assertFalse($sut->exists());
	}

	private function assertExistsReturnsFalseForUnsetId(): void {
		$sut = $this->createDefaultSut();
		$this->assertFalse($sut->exists());
	}

	protected function assertDeleteIsFunctional($validId, $invalidId): void {
		$this->assertDeleteReturnsTrueForKnownId($validId);
		$this->assertDeleteReturnsFalseForUnknownId($invalidId);
		$this->assertDeleteReturnsFalseForUnsetId();
	}

	private function assertDeleteReturnsTrueForKnownId($validId): void {
		$sut = $this->createSutWithId($validId);
		$this->assertTrue($sut->delete());
	}

	private function assertDeleteReturnsFalseForUnknownId($invalidId): void {
		$sut = $this->createSutWithId($invalidId);
		$this->assertFalse($sut->delete());
	}

	private function assertDeleteReturnsFalseForUnsetId(): void {
		$sut = $this->createDefaultSut();
		$this->assertFalse($sut->delete());
	}
}
