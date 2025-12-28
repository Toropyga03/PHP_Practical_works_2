<?php

class User {
    private $connection;
    
    public function __construct() {
        $host = 'localhost';
        $dbname = 'users_db';
        $username = 'root';
        $password = '';
        
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }

    public function create($data) {
        $sql = "INSERT INTO Users (email, first_name, last_name, age, date_created) 
                VALUES (:email, :first_name, :last_name, :age, NOW())";
        
        $stmt = $this->connection->prepare($sql);
        
        return $stmt->execute([
            ':email' => $data['email'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':age' => $data['age']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE Users 
                SET email = :email, 
                    first_name = :first_name, 
                    last_name = :last_name, 
                    age = :age 
                WHERE id = :id";
        
        $stmt = $this->connection->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id,
            ':email' => $data['email'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':age' => $data['age']
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM Users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }
    
    public function list() {
        $sql = "SELECT * FROM Users ORDER BY id DESC";
        $stmt = $this->connection->query($sql);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>