<?php

/**
 * Generates a database, and populates it with sample data.
 *
 * @author Troy Derrick <s3202752@student.rmit.edu.au>
 * @author Diane Foster <s3387562@student.rmit.edu.au>
 * @author Allen Goodreds <s3492264@student.rmit.edu.au>
 * @author Grant Kinkead <s3444261@student.rmit.edu.au>
 * @author Edwan Putro <s3418650@student.rmit.edu.au>
 */
class DatabaseGenerator
{
    /**
     * Executes the SQL file at the given path, against the given database.
     *
     * @param PDO $pdo      The database against which to run the script.
     * @param string $path  The path to an SQL script file.
     */
    public static function RunScript(PDO $pdo, string $path) : void {
        $file = fopen($path, 'r');
        $query = fread($file, filesize($path));
        fclose($file);

        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    /**
     * Creates the table structure.
     *
     * @param PDO $pdo      The database in which to create the tables.
     */
    public static function Generate(PDO $pdo): void {
        self::RunScript($pdo, __DIR__.'/createDb.sql');
    }

    /**
     * Populates the database with sample data.
     *
     * @param PDO $pdo      The database to be populated.
     */
    public static function Populate(PDO $pdo): void {
        self::RunScript($pdo,__DIR__.'/insertUsers.sql');
        self::RunScript($pdo,__DIR__.'/insertCategories.sql');
        self::RunScript($pdo,__DIR__.'/insertItems1.sql');
        self::RunScript($pdo,__DIR__.'/insertItems2.sql');
        self::RunScript($pdo,__DIR__.'/insertItems3.sql');
        self::RunScript($pdo,__DIR__.'/insertItems4.sql');
        self::RunScript($pdo,__DIR__.'/insertItems5.sql');
        self::RunScript($pdo,__DIR__.'/insertItems6.sql');
        self::RunScript($pdo,__DIR__.'/insertItems7.sql');
        self::RunScript($pdo,__DIR__.'/insertItems8.sql');
        self::RunScript($pdo,__DIR__.'/insertItems9.sql');
        self::RunScript($pdo,__DIR__.'/insertItems10.sql');
        self::RunScript($pdo,__DIR__.'/insertItems11.sql');
        self::RunScript($pdo,__DIR__.'/insertItems12.sql');
        self::RunScript($pdo,__DIR__.'/insertItems13.sql');
        self::RunScript($pdo,__DIR__.'/insertItems14.sql');
        self::RunScript($pdo,__DIR__.'/insertItems15.sql');
        self::RunScript($pdo,__DIR__.'/insertItems16.sql');
        self::RunScript($pdo,__DIR__.'/insertItems17.sql');
        self::RunScript($pdo,__DIR__.'/insertItemCategories.sql');
    }

    /**
     * Creates indexes to support full-text search.
     *
     * @param PDO $pdo      The database in which to create the indexes.
     */
    public static function CreateFullTextIndex(PDO $pdo): void {
        self::RunScript($pdo,__DIR__.'/createFullTextIndex.sql');
    }
}
