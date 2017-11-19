<?php

/**
 * Controller for application help-rated functions.
 *
 * @author Troy Derrick 	<s3202752@student.rmit.edu.au>
 * @author Diane Foster 	<s3387562@student.rmit.edu.au>
 * @author Allen Goodreds 	<s3492264@student.rmit.edu.au>
 * @author Grant Kinkead 	<s3444261@student.rmit.edu.au>
 * @author Edwan Putro 		<s3418650@student.rmit.edu.au>
 */
class HelpController {

	/**
	 *	Displays the 'About Us' page.
	 */
	public function About() {
		$this->DisplayPage('about');
	}

	/**
	 *	Displays the privacy policy.
	 */
	public function PrivacyPolicy() {
		$this->DisplayPage('privacyPolicy');
	}

	/**
	 *	Displays the terms of service.
	 */
	public function TermsOfService() {
		$this->DisplayPage('terms');
	}

	/**
	 *	Displays the frequently asked questions.
	 */
	public function Faq() {
		$this->DisplayPage('faq');
	}

	/**
	 *	Displays the site map.
	 */
	public function SiteMap() {
		$this->DisplayPage('siteMap');
	}

	/**
	 * 	Displays the named page template.
	 *
	 * @param string $templateName		The name of the template to be displayed.
	 */
	private function DisplayPage(string $templateName) {
		$view = new View();
		$view->SetData('navData', new NavData(NavData::Home));
		$view->Render($templateName);
	}
}
