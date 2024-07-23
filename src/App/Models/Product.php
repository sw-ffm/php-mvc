<?php 

namespace App\Models;

use PDO;

class Product
{
    public function getData(): array 
    {
        $dns = "mysql:host=localhost;dbname=phpmvc;charset=utf8;port=3306";

        $pdo = new PDO($dns, "phpmvc", "pass123", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $stmt = $pdo->query("SELECT * FROM product");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}