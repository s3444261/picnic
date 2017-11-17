<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

require_once  __DIR__ . '/../config/Picnic.php';

class HomeController {

	/**
	 * Displays the home page.
	 */
	public function index() {
		$h = new Humphree(Picnic::getInstance());

		$view = new View();
		$view->SetData('categories', $h ->getCategoriesIn(Category::ROOT_CATEGORY));
		$view->SetData('navData',  new NavData(NavData::Home));
		$view->Render('home');
	}
}
