<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

declare ( strict_types = 1 );

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
	 * @param int $id The ID to be assigned to the object.
	 */
	abstract protected function createSutWithId(int $id);
	
	/**
	 * Gets an ID of an object that should be valid for the test.
	 */
	abstract protected function getValidId();
	
	/**
	 * Gets an ID of an object that should NOT be valid for the test.
	 */
	abstract protected function getInvalidId();

	/**
	 * Sets attributes on $sut, using the values passed in the associative
	 * array $desiredValues.
	 *
	 * @param $sut The object on which to set attributes.
	 * @param array $desiredValues An associative array of attribute names vs values.
	 */
	protected function setAttributes($sut, $desiredValues): void {
		foreach ( $desiredValues as $x => $x_value ) {
			$sut->$x = $x_value;
		}
	}

	/**
	 * Verifies that $sut has attributes set as specified in the associative
	 * array $expectedValues.
	 *
	 * @param  $sut The object whose attributes will be tested.
	 * @param array $expectedValues An associative array of attribute names vs values.
	 */
	protected function assertValuesAreEqualTo($sut, $expectedValues): void {
		foreach ( $expectedValues as $x => $x_value ) {
			$this->assertEquals ( $x_value, $sut->$x );
		}
	}
	protected function assertAttributesAreSetAndRetrievedCorrectly($values): void {
		$sut = $this->createDefaultSut ();
		$this->setAttributes ( $sut, $values );
		$this->assertValuesAreEqualTo ( $sut, $values );
	}
}
