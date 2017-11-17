<?php
/*
 * Authors:
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - s3418650@student.rmit.edu.au
 */

class NavData
{
	const Home = 0;
	const ViewListings = 1;
	const Account = 2;

	function __construct(int $selected) {
		$this->Selected = $selected;
	}

	public $Selected = self::Home;
}
