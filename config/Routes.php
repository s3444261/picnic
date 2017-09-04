<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 *
 * Routes Class
 * 
 * The Route Class interprets the URL and passes on the parameters.
 * It is a singleton.
 */
class Routes {
	
	// Attributes.
	private $_base = '';
	private $_controller = '';
	private $_action = '';
	private $_id = '';
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
	
	public function setRoute(){
		$uri = str_replace($this->_base, '', $_SERVER ['REQUEST_URI']);
		$count = substr_count($uri, '/');
		
		if($count == 0 || $count > 2){
			$this->_controller = $uri;
		} elseif($count == 1){
			$uri = explode('/', $uri);
			$this->_controller = $uri[0];
			$this->_action = $uri[1];
		} elseif($count == 2){
			$uri = explode('/', $uri);
			$this->_controller = $uri[0];
			$this->_action = $uri[1];
			$this->_id = $uri[2];
		}
	}
}
?>