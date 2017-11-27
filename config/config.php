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
    gethostname() == 'Newbie' ||
    gethostname() == 'DESKTOP-HBREVOT'){
    define('BASE', '/picnic');
} else {
    define('BASE', '');
}
class PicnicAutoloader {
    public static function picnicAutoload($class) {
        $dir = [
            'controller/',
            'model/',
            'view/',
            'view/classes/'
        ];
        foreach ( $dir as $directory ) {
            if (file_exists ( $directory . $class . '.php' )) {
                require_once ($directory . $class . '.php');
                return;
            }
        }
    }
}

spl_autoload_register(['PicnicAutoloader', 'picnicAutoload'], true, true);
