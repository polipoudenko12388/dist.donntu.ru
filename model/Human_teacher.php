<?php
include_once "Human.php";


class Human_Teacher extends Human
{
    public $name_role;
    public $name_status;
    public $name_position;
    public $name_institute;
    public $name_faculty;
    public $name_department;

    public function __construct($db) { parent::__construct($db); }

    public function find_teacher_dbubiversity($snils_inn)
    {
        $object = parent::find_human_dbubiversity($snils_inn);

        // его нет в БД в целом
        if (!$object) return ['info'=>0];
        else 
        {
            $query =  $this->conn->prepare("Select `dbuniversity`.`workes`.`id_human`, `dbuniversity`.`role`.`name` as `name_role`, `dbuniversity`.`status`.`name` as `name status`, `dbuniversity`.`position`.`name` as `name_position`, 
            `dbuniversity`.`institute`.`name` as `name_institute`, `dbuniversity`.`faculty`.`name` as `name_faculty`,  `dbuniversity`.`department`.`name` as `name_department`
            from `dbuniversity`.`human` right join `dbuniversity`.`workes`  on `dbuniversity`.`human`.`id`= `dbuniversity`.`workes`.`id_human`
             inner join `dbuniversity`.`role` on `dbuniversity`.`role`.`id`=`dbuniversity`.`workes`.`id_role`
             inner join `dbuniversity`.`status` on `dbuniversity`.`status`.`id`=`dbuniversity`.`workes`.`id_statust_workes`
             inner join `dbuniversity`.`position` on `dbuniversity`.`position`.`id` = `dbuniversity`.`workes`.`id_position`
             left join  `dbuniversity`.`institute` on `dbuniversity`.`institute`.`id` = `dbuniversity`.`workes`.`id_institute`
            inner join  `dbuniversity`.`faculty` on `dbuniversity`.`faculty`.`id` = `dbuniversity`.`workes`.`id_faculty`
            inner join  `dbuniversity`.`department` on `dbuniversity`.`department`.`id` = `dbuniversity`.`workes`.`id_department`
            where `dbuniversity`.`workes`.`id_human`=? and (`dbuniversity`.`workes`.`id_role`=2 and `dbuniversity`.`workes`.`id_statust_workes`=5) ");
            $query->execute([$object['id']]);
            $result = $query->fetch(PDO::FETCH_BOTH);

            if ($result) 
            {
                $this->id=$object['id']; $this->surname=$object['surname']; $this->name=$object['name'];     $this->patronymic=$object['patronymic']; $this->datebirth=$object['datebirth'];
                $this->email=$object['email']; $this->phone=$object['phone']; $this->photo=$object['photo']; $this->snils=$object['snils'];
                 $this->inn=$object['inn']; $this->name_country=$object['name_country']; $this->name_regions = $object['name_regions'];
                $this->name_type=$object['name_type']; $this->name_settlements=$object['name_settlements'];
                
                $this->name_role=$result['name_role']; $this->name_status=$result['name status']; $this->name_position=$result['name_position']; 
                $this->name_institute=$result['name_institute']; $this->name_faculty=$result['name_faculty']; $this->name_department=$result['name_department'];

                // человек есть в БД как преподаватель
                return ['info'=>1];
            }
            else return ['info'=>2];
        }  
    }

}

?>