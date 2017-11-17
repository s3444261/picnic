<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

class ContactController {
	
	// Displays the Contact Page.
	public function index()
	{
		$view = new View();
		$view->SetData('navData',  new NavData(NavData::Home));
		$view->Render('contact');
	}
}
