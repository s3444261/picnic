<?php

include __DIR__ . '/../../../dbPicnic.php';

/**
 * A wrapper providing access to the application database.
 *
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class Picnic extends PDO {
    private static $_instance;

    /**
     * Gets the singleton instance of this class.
     *
     * @return Picnic   The singleton instance.
     */
    public static function getInstance(): Picnic {
        if (!isset(self::$_instance )) {

            self::$_instance = new Picnic (
                'mysql:host='
                . getenv("DB_HOST")
                . ';dbname=' . getenv("DB_NAME"),
                getenv("DB_USER"),
                getenv("DB_PW"));
            
            // Used to document errors during development.
            self::$_instance->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        }

        return self::$_instance;
    }
}

