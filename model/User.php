<?php

class User
{
 
    protected $conn;
    public $id_user;
    public $surname;
    public $name;
    public $patronymic;
    public $email;
    public $phone;
    public $photo;
    public $snils;
    public $inn;

    public function __construct($db)
    {
        $this->conn = $db;  
    }

    public function find_human_dbwebsite($snils_inn)
    {
        // запрос, есть ли человек по инн или снилку в бд сайта
        $query =  $this->conn->prepare("select `dbwebsite_university`.`user`.`id`, `dbwebsite_university`.`user`.`surname`, `dbwebsite_university`.`user`.`name`, `dbwebsite_university`.`user`.`patronymic`, `dbwebsite_university`.`user`.`email`,
        `dbwebsite_university`.`user`.`phone`, `dbwebsite_university`.`user`.`photo`, `dbwebsite_university`.`user`.`snils`, `dbwebsite_university`.`user`.`inn`
       from `dbwebsite_university`.`user` where (`dbwebsite_university`.`user`.`snils`= ? or `dbwebsite_university`.`user`.`inn`= ?)");
        $query->execute([$snils_inn, $snils_inn]);
        $result = $query->fetch(PDO::FETCH_BOTH);

        return $result;
    }

    public function printfInfoUser($id_user)
    {
        $query =  $this->conn->prepare("SELECT * FROM `dbwebsite_university`.`user` where `dbwebsite_university`.`user`.`id`= ?");
        $query->execute([$id_user]);
        $result = $query->fetch(PDO::FETCH_BOTH);

        return $result;
    }

    public function InsertUser($surname, $name, $patronymic, $email, $phone, $photo, $snils, $inn)
    {
        $query =  $this->conn->prepare("INSERT INTO `dbwebsite_university`.`user` (`surname`, `name`, `patronymic`, `email`, `phone`, `photo`, `snils`, `inn`) 
        VALUES (?, ?, ?, ?, ?, ?,?, ?)");
        $query->execute([$surname, $name, $patronymic, $email, $phone, $photo, $snils, $inn]);
        
        return  $query;
    }
}

?>