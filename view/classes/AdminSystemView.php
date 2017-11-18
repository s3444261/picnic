<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class AdminSystemView extends View{

	function __construct() {
		$this->SetData('navData', new NavData(NavData::Account));
	}

	public function usersUrl(): string {
		return BASE . '/Administration/Users';
	}

	public function categoriesUrl(): string {
		return BASE . '/Administration/ViewCategories';
	}

	public function systemUrl(): string {
		return BASE . '/Administration/System';
	}

	public function rebuildDbUrl(): string {
		return BASE . '/Administration/RebuildDatabase';
	}
}
