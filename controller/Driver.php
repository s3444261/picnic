<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 *
 * Driver Class
 * 
 * The Driver Class is the controller of the application. It determines what is
 * being requested and displays the appropriate views with content that has been
 * manufactured by the model.
 */
class Driver {
	
	// Attributes.
	private static $_instance;
	
	// Constructor.
	function __construct($args = array()) {
		foreach ( $args as $key => $val ) {
			$name = '_' . $key;
			if (isset ( $this->{$name} )) {
				$this->{$name} = $val;
			}
		}
	}
	
	// Getter.
	public function &__get($name) {
		$name = '_' . $name;
		return $this->$name;
	}
	
	// Setter.
	public function __set($name, $value) {
		$name = '_' . $name;
		$this->$name = $value;
	}
	
	// Singleton Pattern.
	public static function getInstance() {
		if (! isset ( self::$_instance )) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}
	
	// Displays either the search form or the results.
	// Dependant on the request.
	public function display() {
		include 'view/header/header.php';
		include 'view/nav/nav.php';
		echo $this->pageContent();
		include 'view/footer/footer.php';
	}
	
	public function pageContent(){
		$routes = Routes::getInstance();
		$home = new HomeController ();
		$about = new AboutController ();
		$contact = new ContactController ();
		$settings = new SettingsController ();
		$changePassword = new ChangePasswordController ();
		$login = new LoginController ();
		$logout = new LoginController ();
		$test = new TestController ();
		$createDB = new CreateDBController ();
		
		switch ($routes->controller) {
			case 'Home' :
				$home->display ();
				break;
			case 'About' :
				$about->display ();
				break;
			case 'Contact' :
				$contact->display ();
				break;
			case 'Settings' :
				if($this->auth()){
					$settings->display ();
				} else {
					$home->display ();
				}
				break;
			case 'ChangePassword' :
				if($this->auth()){
					$changePassword->display ();
				} else {
					$home->display ();
				}
				break;
			case 'Login' :
				$login->login ();
				break;
			case 'Logout' :
				$logout->logout ();
				break;
			case 'Test' :
				if($this->auth()){
					$test->display ();
				} else {
					$test->display ();
					//$home->display ();
				}
				break;
			case 'CreateDB' :
				if($this->auth()){
					$createDB->display ();
				} else {
					$createDB->display ();
					//$home->display ();
				}
				break;
			default :
				$home->display ();
				break;
		}
	}
	
	private function auth(){
		if(isset($_SESSION[MODULE]) && isset($_SESSION['userID'])){
			if($_SESSION['userID'] > 0){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>