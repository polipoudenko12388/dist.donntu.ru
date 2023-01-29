<?php

include_once "../model/Discipline.php";

class Discipline_flow
{
    private $conn;
    public $id_discipline;
    public $number_hours_reading;
    public $id_flow;
    // преподы
    // объект поток 
    // объект дисциплина

    public function __construct($db)  { $this->conn=$db; }


    public function findDublicate($id_discipline, $id_flow)
    {
        $query =  $this->conn->prepare("SELECT * FROM `dbwebsite_university`.`discipline_flow` 
        where `dbwebsite_university`.`discipline_flow`.`id_list_discipline` = ? and `dbwebsite_university`.`discipline_flow`.`id_flow` = ?");  
        $query->execute([$id_discipline, $id_flow]);
        $result = $query->fetch(PDO::FETCH_BOTH);
        return $result;
    }
    // добавление потока в дисциплину 
    public function InsertDiscipline_flow($id_discipline, $number_hours_reading, $id_flow, $id_teacher_ovner)
    {
        // проверка, что добавление потоков делает владелец дисциплины
        $result = Discipline::TestdelOrEdit($id_discipline, $id_teacher_ovner, $this->conn);

        // добавление делает владелец своей дисциплины
        if ($result)
        {
            // проверка на добавление дубликатов 
            unset($result); 
            $result = $this->findDublicate($id_discipline, $id_flow);

            // если дубликат присутствует
            if ($result) return false;

            else
            {
                unset($query); 
                $query =  $this->conn->prepare("INSERT INTO `dbwebsite_university`.`discipline_flow` (`id_list_discipline`, `number_hours_reading`, `id_flow`)
                VALUES (?,?,?)");  
                $query->execute([$id_discipline, $number_hours_reading, $id_flow]);
                return $query;
            }
        }
        return false;
    }

    public function InsertTeacheronFlowDiscipline($id_discipline, $number_hours_reading, $id_flow, $id_teacher_ovner, $array_idteacher)
    {
        $result = $this->InsertDiscipline_flow($id_discipline, $number_hours_reading, $id_flow, $id_teacher_ovner);

        // если добавление в таблицу "Дисциплины-потоки" прошло успешно
        if ($result)
        {
            // если владелец доб. других преподов в поток дисциплины ($array_idteacher - хранит id добавленных преподов)
            if ($array_idteacher)
            {
                unset($result);
                $result = $this->findDublicate($id_discipline, $id_flow);

                for ($i=0; $i<count($array_idteacher); $i++)
                {
                    $query =  $this->conn->prepare("INSERT INTO `dbwebsite_university`.`teacher_discipline` (`id_discipline_flow`, `id_teacher`) VALUES (?, ?)");  
                    $query->execute([$result['id'], $array_idteacher[$i]]);
                }
                if ($query) return ["info"=>"Добавление потока прошло успешно. Преподаватели  были добавлены."];
                else return  ["error"=>"Добавление потока прошло успешно. Преподаватели не были добавлены."]; 
            }
            return  ["info"=>"Добавление потока прошло успешно. Преподаватели не были выбраны."];
        }
        else return ["error"=>"Добавление в таблицу 'Дисциплины-потоки' не произошло. Проверьте свои данные. Возможен дубликат."];
    }

    public function printIdDiscipline()
    {
        $query =  $this->conn->prepare("SELECT `id`, `name`, `fon` FROM   `dbwebsite_university`.`list_disciplines`");  
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_BOTH);
        return $result; 
    }

    public function printDisciplineonTeacher($id_teacher)
    {
        $result = $this->printIdDiscipline();

        if ($result)
        {
            $data_array = array();
            for($i=0; $i<count($result); $i++)
            {
                $query =  $this->conn->prepare("SELECT DISTINCT `dbwebsite_university`.`discipline_flow`.`id`, `dbwebsite_university`.`discipline_flow`.`id_flow` , 
                `dbwebsite_university`.`flow`.`name` as `name_flow`
                FROM  `dbwebsite_university`.`discipline_flow` 
                inner join `dbwebsite_university`.`flow` on `dbwebsite_university`.`flow`.`id` = `dbwebsite_university`.`discipline_flow`.`id_flow`
                inner join `dbwebsite_university`.`teacher_discipline` on  `dbwebsite_university`.`teacher_discipline`.`id_discipline_flow` = `dbwebsite_university`.`discipline_flow`.`id`
                inner join `dbwebsite_university`.`list_disciplines` on `dbwebsite_university`.`discipline_flow`.`id_list_discipline`= `dbwebsite_university`.`list_disciplines`.`id`
                where  `dbwebsite_university`.`list_disciplines`.`id` = ?
                and (`dbwebsite_university`.`list_disciplines`.`id_teacher`= ? or `dbwebsite_university`.`teacher_discipline`.`id_teacher`= ?)");  
                $query->execute([$result[$i]['id'], $id_teacher, $id_teacher]);
                $resultFlow = $query->fetchall(PDO::FETCH_BOTH);


                // $data_array[] = array("id_list_discipline"=>$result[$i]['id'], "name_disc"=> $result[$i]['name'], "fon" => $result[$i]['fon'],
                // "array_flows"=> null);
                // if ($resultFlow)
                // {
                //     for ($j=0; $j<count($resultFlow); $j++)
                //     {
                //         $data_array[$i]["array_flows"][$j] =  array("id_flow"=>$resultFlow[$j]['id_flow'], "name_flow"=>$resultFlow[$j]['name_flow']);
                //     }    
                // }

                
                if ($resultFlow)
                {
                    $data_array[] = array("id_list_discipline"=>$result[$i]['id'], "name_disc"=> $result[$i]['name'], "fon" => $result[$i]['fon']);
                    for ($j=0; $j<count($resultFlow); $j++)
                    {
                        $data_array[$i]["array_flows"][$j] =  array("id_flow"=>$resultFlow[$j]['id_flow'], "name_flow"=>$resultFlow[$j]['name_flow']);
                    }    
                }
                else $data_array[] = array("id_list_discipline"=>null, "name_disc"=> null, "fon" => null, "array_flows"=> null);
            }
            return $data_array;
        }
        else return false;
    }

}
?>