<?php
/**
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */

error_reporting ( E_ALL );
ini_set ( 'display_errors', 1 );
date_default_timezone_set('Australia/Melbourne');
define('MODULE', '');
if(gethostname() == 'Grant-PC' ||
    gethostname() == 'Newbie') {
    define('BASE', '/picnic');
} else {
    define('BASE', '');
}

/**
 * Autoloader for Picnic classes.
 */
class PicnicAutoloader {
    /**
     * Automatically includes classes within this application.
     *
     * @param string $className    The name of the class to be included.
     */
    public static function picnicAutoload(string $className) {
        $dir = [
            'controller/',
            'model/',
            'view/',
            'view/classes/'
        ];

        foreach ( $dir as $directory ) {
            $fullPath =  $directory . $className . '.php';
            if (file_exists ($fullPath)) {

                require_once ($fullPath);
                return;
            }
        }
    }
}

spl_autoload_register(['PicnicAutoloader', 'picnicAutoload'], true, true);
