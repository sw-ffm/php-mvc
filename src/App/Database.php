<?php 

namespace App;

use PDO;

class Database 
{
    public function getConnection(): PDO
    {
        $dns = "mysql:host=localhost;dbname=phpmvc;charset=utf8;port=3306";

        return new PDO($dns, "phpmvc", "pass123", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}