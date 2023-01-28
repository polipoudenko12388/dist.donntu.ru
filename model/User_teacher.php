<?php

include_once "../model/User.php";

class User_teacher extends User
{
    public $id_teacher;
    public $rights_admin;
    public $position;

    public function __construct($db) { parent::__construct($db); }

    // поиск человека в таблице преподавателей
    public function find_teacher_dbwebsite($snils_inn)
    {
        $object = parent::find_human_dbwebsite($snils_inn);

        // его нет в БД в целом
        if (!$object) return ['info'=>1];
        else 
        {
            $query =  $this->conn->prepare("SELECT `dbwebsite_university`.`teacher`.`id`, `dbwebsite_university`.`teacher`.`rights_admin`, `dbwebsite_university`.`teacher`.`position`  
            FROM `dbwebsite_university`.`teacher` where `dbwebsite_university`.`teacher`.`id_user` = ?");
            $query->execute([$object['id']]);
            $result = $query->fetch(PDO::FETCH_BOTH);

            if ($result) return ['info'=>0]; // человек зарегистрирован
            else return ['info'=>2]; // человека нет в таблице преподаватели
        }  
    }

    // вывод инфы о преподавателе, который уже зарегистрирован в бд сайта по id пользователя
    public function printfInfoTeacher($id_user)
    {
        $object = parent::printfInfoUser($id_user);

        if (!$object) return false;
        else 
        {
            $query =  $this->conn->prepare("SELECT * FROM `dbwebsite_university`.`teacher` where `dbwebsite_university`.`teacher`.`id_user`= ?");
            $query->execute([$id_user]);
            $result = $query->fetch(PDO::FETCH_BOTH);

            if ($result) 
            {
                $this->id_user=$object['id']; $this->surname=$object['surname']; $this->name=$object['name'];     $this->patronymic=$object['patronymic']; 
                $this->email=$object['email']; $this->phone=$object['phone']; $this->photo=$object['photo']; $this->snils=$object['snils']; $this->inn=$object['inn']; 
                $this->id_teacher=$result['id']; $this->rights_admin=$result['rights_admin']; $this->position=$result['position']; 
               
                return true;
            }
            else return false;
        }  
    }

    public function printfTeacher()
    {
        $query =  $this->conn->prepare("SELECT `dbwebsite_university`.`teacher`.`id` as `id_teacher`, `dbwebsite_university`.`user`.`surname`,  
        `dbwebsite_university`.`user`.`name`, `dbwebsite_university`.`user`.`patronymic`  
        FROM `dbwebsite_university`.`teacher` inner join `dbwebsite_university`.`user` on `dbwebsite_university`.`teacher`.`id_user` = `dbwebsite_university`.`user`.`id`");
        
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_BOTH);
        return $result;
    }

    public function printfTeacherinFlow($id_teacher_ovner)
    {
        $query =  $this->conn->prepare("SELECT `dbwebsite_university`.`teacher`.`id` as `id_teacher`, `dbwebsite_university`.`user`.`surname`,  
        `dbwebsite_university`.`user`.`name`, `dbwebsite_university`.`user`.`patronymic`  
        FROM `dbwebsite_university`.`teacher` inner join `dbwebsite_university`.`user` on `dbwebsite_university`.`teacher`.`id_user` = `dbwebsite_university`.`user`.`id`
        where `dbwebsite_university`.`teacher`.`id`!= ? ");
        
        $query->execute([$id_teacher_ovner]);
        $result = $query->fetchall(PDO::FETCH_BOTH);
        return $result;
    }


    // добавление препода
    public function InsertTeacher($id_user, $position)
    {
        $query =  $this->conn->prepare("INSERT INTO `dbwebsite_university`.`teacher` (`id_user`, `rights_admin`, `position`) VALUES (?,?,?)");
        $query->execute([$id_user,1, $position]);
        
        return  $query;
    }


}
?>