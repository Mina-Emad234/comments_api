<?php

use JetBrains\PhpStorm\NoReturn;

class QueryBuilder{ //make my sql queries need pdo connection class as a param used when build class

    private $pdo;
    private $table_name = "comments";

    public $id;
    public $name;
    public $email;
    public $body;

    public function __construct($pdo)
    {
        $this->pdo=$pdo;
    }
    public function prepare($sql){
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->body=htmlspecialchars(strip_tags($this->body));
        $sql->bindParam(":name",$this->name);
        $sql->bindParam(":email",$this->email);
        $sql->bindParam(":body",$this->body);
    }

    #[NoReturn]
    public function insertCommentsApi(){
        $sql = $this->pdo->prepare("INSERT INTO $this->table_name (`name`,`email`,`body`)VALUES (:name,:email,:body)");
        $this->prepare($sql);
        $sql->execute();
        return $sql->rowCount();
    }


    public function get_one_comment(): array
    {
        $sql = $this->pdo->prepare("SELECT * FROM $this->table_name WHERE id=?");
        $sql->bindParam(1,$this->id);
        $sql->execute();
        $comments_arr["records"]=[];
        if($sql->rowCount() > 0){
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)){
                extract($row);//get key as variables
                $comment_content=array(
                    "id" => $id,
                    "name" => $name,
                    "email" => $email,
                    "body" => $body
                );

                array_push($comments_arr["records"], $comment_content);

            }
        }
        return $comments_arr;
    }

    public function updateComment(){
        $sql = $this->pdo->prepare("UPDATE $this->table_name SET `name`=:name, `email`=:email,`body`=:body WHERE id=:id");
        $this->id=htmlspecialchars(strip_tags($this->id));
        $sql->bindParam(':id',$this->id);
        $this->prepare($sql);
        $sql->execute();
        return $sql->rowCount();
    }

    public function deleteComment(){
        $sql = $this->pdo->prepare("DELETE FROM $this->table_name WHERE id=:id");
        $this->id=htmlspecialchars(strip_tags($this->id));
        $sql->bindParam(':id',$this->id);
        $sql->execute();
        return $sql->rowCount();
    }


    public function selectItems($items,$table,$condition='',$get='')//select data from table
    {
        $statement = $this->pdo->prepare("SELECT {$items} FROM {$table} {$condition}");
        $statement->execute();
        if($get == '') {
            return $statement->fetch();
        }elseif($get == 1){
            return $statement->fetchAll();
        }elseif($get == 2) {
            return $statement->rowCount();
        }elseif ($get == 3){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    public function selectAllItems($items,$table,$condition='')//select data from table
    {
        $statement = $this->pdo->prepare("SELECT {$items} FROM {$table} {$condition}");
        $statement->execute();
        while($row = $statement->fetchAll()):
            return $row;
        endwhile;

    }

    public function insert($table,$parameters=[])//insert data in tables

    {

        $sql = sprintf(
            'insert into %s (%s) values (%s)',

            $table,

            implode(', ' , array_keys($parameters)),

            ':' . implode(', :' , array_keys($parameters))

        );

        try {

            $statement = $this->pdo->prepare($sql);

            $statement->execute($parameters);

        }catch (Exception $e){

            die('Oops, something went wrong.');

        }

    }



}