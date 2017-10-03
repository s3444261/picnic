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

define('MODULE', '');

if(gethostname() == 'Grant-PC'){
	define('BASE', '/picnic');
} else {
	define('BASE', ''); 
}


function __autoload($class) {
	$dir = array (
			'controller/',
			'model/',
		    'view/'
	);
	
	foreach ( $dir as $directory ) {
		if (file_exists ( $directory . $class . '.php' )) {
			require_once ($directory . $class . '.php');
			return;
		}
	}
}
