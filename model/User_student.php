<?php


class User_student extends User
{
    public $id_student;
    public $id_group;
    public $id_user;

    public function __construct($object) 
    {
    if(is_array($object))
    {
        $this->conn=$object[0];
        $this->printfInfoStudent($object[1]);

    }
    else { parent::__construct($object);  }
        
    }

    // вывод инфы о студенте, который уже зарегистрирован в бд сайта по id пользователя
    public function printfInfoStudent($id_user)
    {
        $object = parent::printfInfoUser($id_user);

        if (!$object) return false;
        else 
        {
            $query =  $this->conn->prepare("SELECT * FROM `dbwebsite_university`.`student` where `dbwebsite_university`.`student`.`id_user`= ?");
            $query->execute([$id_user]);
            $result = $query->fetch(PDO::FETCH_BOTH);

            if ($result) 
            {
                $this->id_user=$object['id']; $this->surname=$object['surname']; $this->name=$object['name'];     $this->patronymic=$object['patronymic']; 
                $this->email=$object['email']; $this->phone=$object['phone']; $this->photo=$object['photo']; $this->snils=$object['snils']; $this->inn=$object['inn']; 
                $this->id_student=$result['id']; $this->id_group=$result['id_group']; $this->id_user=$result['id_user']; 
               
                return true;
            }
            else return false;
        }  
    }
}

?>