<?php


class UserDatabase{ //make my sql queries need pdo connection class as a param used when build class

    private $pdo;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;

    public function __construct($pdo)
    {
        $this->pdo=$pdo;
    }


    public function addUser(){
        $sql = $this->pdo->prepare("INSERT INTO $this->table_name (`name`,`email`,`password`)VALUES (:name,:email,:password)");
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $sql->bindParam(":name",$this->name);
        $sql->bindParam(":email",$this->email);
        // hash the password before saving to database
        $password_hash = md5($this->password);
        $sql->bindParam(':password', $password_hash);
        $sql->execute();
        return $sql->rowCount();
    }

    public function updateUser(){
        $sql = $this->pdo->prepare("UPDATE $this->table_name SET `name`=:name,`email`=:email,`password`=:password where id=:id");
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $sql->bindParam(":name",$this->name);
        $sql->bindParam(":email",$this->email);
        $sql->bindParam(":id",$this->id);
        // hash the password before saving to database
        $password_hash = md5($this->password);
        $sql->bindParam(':password', $password_hash);
        $sql->execute();
        return $sql->rowCount();
    }

    public function login(){
        $sql = $this->pdo->prepare("SELECT * FROM $this->table_name WHERE email=? AND password=? LIMIT 1");
        $this->email=htmlspecialchars(strip_tags($this->email));
        $sql->bindParam(1,$this->email);
        $password_hash = md5($this->password);
        $sql->bindParam(2, $password_hash);
        $sql->execute();
        if($sql->rowCount() > 0){
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            return true;
        }
        return false;
    }

    public function selectAll()//select data from table
    {
        $sql = $this->pdo->prepare("SELECT * FROM {$this->table_name}");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function selectItem()//select data from table
    {
        $statement =  $this->pdo->prepare("SELECT email, password FROM {$this->table_name} where email=? AND password=? LIMIT 1");
        $this->email=htmlspecialchars(strip_tags($this->email));
        $statement->bindParam(1,$this->email);
        $password_hash = md5($this->password);
        $statement->bindParam(2, $password_hash);
        if($statement->execute()){
            return true;
        }
    }

}