<?php

class Discipline
{
    private $conn;
    public $name_discipline;
    public $id_institute;
    public $id_department;
    public $id_faculty;
    public $id_teacher_ovner;
    public $fon;

    public function __construct($db)  { $this->conn=$db; }

    // вывод справочников (институт, факультет, кафедра)
    public function printfDirectory($str_name_table)
    {
        $query =  $this->conn->prepare("SELECT * FROM " .$str_name_table."");  
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_BOTH);
        return $result;    
    }

    public static function printfTeacherId($id_teacher, $conn)
    {
        $query =  $conn->prepare("SELECT * FROM `dbwebsite_university`.`list_disciplines` where `dbwebsite_university`.`list_disciplines`.`id_teacher`=?");  
        $query->execute([$id_teacher]);
        $result = $query->fetchall(PDO::FETCH_BOTH);
        return $result;  
    }
    // проверка на дубликат и добавление данных в "list_disciplines"
    public function InsertDiscipline($name, $id_institute,$id_faculty, $id_department, $id_teacher, $fon)
    {
        $query =  $this->conn->prepare("SELECT * FROM `dbwebsite_university`.`list_disciplines` 
        where (`dbwebsite_university`.`list_disciplines`.`name`= ? and `dbwebsite_university`.`list_disciplines`.`id_institute`=? and
        `dbwebsite_university`.`list_disciplines`.`id_faculty` = ? and `dbwebsite_university`.`list_disciplines`.`id_department` = ? 
        and `dbwebsite_university`.`list_disciplines`.`id_teacher`=?)");  
        $query->execute([$name, $id_institute,$id_faculty, $id_department, $id_teacher]);
        $result = $query->fetch(PDO::FETCH_BOTH);

        if($result) return false;
        else 
        {
            unset($query); 
            $query =  $this->conn->prepare("INSERT INTO `dbwebsite_university`.`list_disciplines` (`name`, `id_institute`, `id_faculty`, `id_department`, `id_teacher`,`fon`)
            VALUES (?,?,?,?,?,?)");  
            $query->execute([$name, $id_institute,$id_faculty, $id_department, $id_teacher, $fon]);
            return $query;
        } 
    }

    // проверка, владелец ли будет редактировать или удалять дисциплину, или кто-то другой может попробовать
    public static function TestdelOrEdit($id_discipline, $id_teacher, $conn)
    {
        $query =  $conn->prepare("SELECT * FROM `dbwebsite_university`.`list_disciplines`  
        where (`dbwebsite_university`.`list_disciplines`.`id` = ? and `dbwebsite_university`.`list_disciplines`.`id_teacher` = ? )");  
        $query->execute([$id_discipline, $id_teacher]);
        $result = $query->fetch(PDO::FETCH_BOTH);

        return $result;
    }

        // вывод инфы о дисциплине по ее id и id препода и делаю проверку, можно ли ее редактировать
        public function printfDisciplineId($id_discipline, $id_teacher)
        {
            $result = $this->TestdelOrEdit($id_discipline, $id_teacher, $this->conn);

            if ($result)
            {
                $this->name_discipline = $result['name']; $this->id_institute = $result['id_institute'];
                $this->id_faculty = $result['id_faculty']; $this->id_department = $result['id_department'];
                $this->id_teacher_ovner = $result['id_teacher']; $this->fon = $result['fon'];

                // выводим инфу о дисциплине, которую хотим отредактировать
                return true;
            }
            // преподаватель не имеет право редактировать дисциплину
            return false;    
        }

        public function UpdateDisciplineId($name, $id_institute,$id_faculty, $id_department, $id_teacher, $fon, $id_discipline)
        {
            try
            {
                $query =  $this->conn->prepare("UPDATE `dbwebsite_university`.`list_disciplines` 
                SET `name` = ?, `id_institute` = ?, `id_faculty` = ?, `id_department` = ?, `id_teacher` = ?, `fon` = ? WHERE (`id` = ?);");  
                $query->execute([$name, $id_institute,$id_faculty, $id_department, $id_teacher, null, $id_discipline]);

            }
            catch(PDOException $e) { $query = false; }

            return $query;
        }

        public function DeleteDisciplineId($id_teacher,  $id_discipline)
        {
            $result = $this->TestdelOrEdit($id_discipline, $id_teacher, $this->conn);
            
            if ($result)
            {
                $query =  $this->conn->prepare("DELETE FROM `dbwebsite_university`.`list_disciplines` WHERE (`id` = ?)");  
                $query->execute([$id_discipline]);
                return $query;
            }
            return false;
        }

}

?>