<?php 

namespace App\Models;

use Framework\Model;
use PDO;

class Product extends Model
{
    //protected $table = "product";

    protected function validate(array $data): void 
    {
        if(empty($data["name"])){
            
            $this->addError("name", "Name is required");

        }

    }

    public function getTotal(): int 
    {
        $sql = "SELECT COUNT(id) AS total from product";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->query($sql);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$data["total"];
    }

    
}