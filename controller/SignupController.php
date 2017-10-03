<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 */

class SignupController  extends BaseController {
	
	// Displays the Create Account Page.
	public function index()
	{
		$this->RenderInMainTemplate('view/layout/signup.php');
	}
}
?>