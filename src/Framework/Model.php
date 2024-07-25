<?php 

namespace Framework;

use PDO;
use App\Database;

abstract class Model
{
    
    protected $table;

    protected array $errors = [];

    
    public function __construct(protected Database $database) 
    {
    }
    
    protected function validate(array $data): void
    {
    }
    
    public function getInsertID(): string 
    {
        $pdo = $this->database->getConnection();
        return $pdo->lastInsertId();
    }

    protected function addError(string $field, string $message): void 
    {
        $this->errors[$field] = $message;
    }

    public function getErrors(): array 
    {
        return $this->errors;
    }

    private function getTable(): string 
    {
        if($this->table !== null){

            return $this->table;

        }
        
        $parts = explode("\\", $this::class);

        return strtolower(array_pop($parts));
    }

    public function findAll(): array 
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT * FROM {$this->getTable()}";

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $id): array|bool
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT * FROM {$this->getTable()} WHERE id=?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert(array $data): bool 
    {   
        $this->validate($data);

        if(!empty($this->errors)){
            return false;
        }

        $pdo = $this->database->getConnection();

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $sql = "INSERT INTO {$this->getTable()} ({$columns}) VALUES ({$placeholders})";

        $stmt = $pdo->prepare($sql);

        $i=1;

        foreach($data as $value){

            $type = match(gettype($value)){
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };

            $stmt->bindValue($i++, $value, $type);
        
        }

        return $stmt->execute();
    }

    public function update(string $id, array $data): bool 
    {   
        $this->validate($data);

        if(!empty($this->errors)){
            return false;
        }

        $pdo = $this->database->getConnection();

        $sql = "UPDATE {$this->getTable()} ";

        unset($data["id"]);

        $assignments = array_keys($data);

        array_walk($assignments, function(&$value){
            $value = "$value=?";
        });

        $sql .= "SET " . implode(", ", $assignments);
        $sql .= " WHERE id=?";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $i=1;

        foreach($data as $value){

            $type = match(gettype($value)){
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };

            $stmt->bindValue($i++, $value, $type);
        
        }$stmt->bindValue($i, $id, PDO::PARAM_INT);

        return $stmt->execute();        
        
    }

    public function delete(string $id): bool 
    {
        $pdo = $this->database->getConnection();

        $sql = "DELETE FROM {$this->getTable()} WHERE id = :id"; 

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();  
    }

}