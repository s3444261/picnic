<?php
/* Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

/*
 *  Base test class, providing commonly used methods.
 */
abstract class PicnicTestCase extends PHPUnit\Framework\TestCase
{
	abstract protected function createDefaultSut();
	abstract protected function createSutWithId($id);

	/*
	 *  Sets attributes on $obj, with the values passed in the associative array $values.
	 */
	protected function setAttributes($sut, $desiredValues): void {
		foreach ($desiredValues as $x=>$x_value) {
			$sut->$x = $x_value;
		}
	}

	/*
	 *  Verifies that $obj has attributes set as specified in the associative array $values.
	 */
	protected function assertValuesAreEqualTo($sut, $expectedValues): void {
		foreach ($expectedValues as $x=>$x_value) {
			$this->assertEquals($x_value, $sut->$x);
		}
	}

	protected function assertAttributesAreSetAndRetrievedCorrectly($values): void
	{
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
