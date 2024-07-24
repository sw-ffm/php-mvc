<?php 

namespace App;

use PDO;

class Database 
{

    private ?PDO $pdo=null;

    public function __construct(private string $host,
                                private string $name,
                                private string $user,
                                private string $password)
    {
    }

    public function getConnection(): PDO
    {
        if($this->pdo === null){

            $dns = "mysql:host={$this->host};dbname={$this->name};charset=utf8;port=3306";

            $this->pdo = new PDO($dns, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

        }

        return $this->pdo;

    }
}