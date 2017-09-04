<?php
/*
 * Authors: 
 * Derrick, Troy - s3202752@student.rmit.edu.au
 * Foster, Diane - s3387562@student.rmit.edu.au
 * Goodreds, Allen - s3492264@student.rmit.edu.au
 * Kinkead, Grant - s3444261@student.rmit.edu.au
 * Putro, Edwan - edwanhp@gmail.com
 *
 * Config.php
 */ 
error_reporting ( E_ALL );
ini_set ( 'display_errors', 1 );

date_default_timezone_set('Australia/Melbourne');
define('MODULE', 'picnic');
define('BASE', '/' . MODULE . '/');
define('SALT', 'TooMuchSalt');

if($_SERVER['SERVER_NAME'] == 'localhost'){
	define('SERVER', 'http://' . $_SERVER['SERVER_NAME'] . ':8080/');
	define('PATH', SERVER . MODULE . '/');
} elseif($_SERVER['SERVER_NAME'] == '52.62.222.222'){
	define('SERVER', 'http://' . $_SERVER['SERVER_NAME']);
	define('PATH', SERVER . '/picnic/');
} elseif($_SERVER['SERVER_NAME'] == 'www.toberegistered.org'){
	define('SERVER', 'http://' . $_SERVER['SERVER_NAME']);
	define('PATH', SERVER . '/');
} else {
	define('SERVER', 'http://' . $_SERVER['SERVER_NAME']);
	define('PATH', SERVER . '/');
}
define('DOMAIN', 'www.toberegistered.org');

function __autoload($class) {
	$dir = array (
			'config/',
			'connect/',
			'controller/',
			'model/'
	);
	
	foreach ( $dir as $directory ) {
		if (file_exists ( $directory . $class . '.php' )) {
			require_once ($directory . $class . '.php');
			return;
		}
	}
}

$routes = Routes::getInstance();
$routes->base = BASE;
$routes->setRoute();

?>