<?php

class Group
{
    private $conn;
    public $id_group;
    public $name_group;
    public $Students = array(); 

    public function __construct($db, $id_group) 
    {
        $this->conn=$db;
        $this->printfStudentsGroup($id_group);
    }

    // поиск и сохранение в массив студентов опред. группы по ip группы
    public function printfStudentsGroup($id_group)
    {
        $query =  $this->conn->prepare("SELECT `dbwebsite_university`.`student`.`id`  as `id_student`, `dbwebsite_university`.`student`.`id_user`, 
        `dbwebsite_university`.`student`.`id_group`, `dbwebsite_university`.`group`.`name` as `name_group` 
        FROM `dbwebsite_university`.`student` inner join `dbwebsite_university`.`group` on `dbwebsite_university`.`student`.`id_group`= `dbwebsite_university`.`group`.`id`
        where `dbwebsite_university`.`student`.`id_group`= ?");
        $query->execute([$id_group]);
        $result = $query->fetchall(PDO::FETCH_BOTH);

       
        if (count($result)>0)
        {
            for ($i=0; $i < count($result); $i++)
            {
                $this->Students[] = new User_student([$this->conn, $result[$i]['id_user']]);
            }

            $this->id_group = $result[0]['id_group']; $this->name_group = $result[0]['name_group'];
            return true;

        }

        else return false; 
    }
}
?>