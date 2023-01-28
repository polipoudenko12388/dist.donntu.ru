<?php

class Flow
{
    private $conn;
    public $id_flow;
    public $name_flow;
    public $Groups = array(); 

    public function __construct($db) 
    {
        $this->conn=$db;
    }

    // поиск потоков
    public static function printfFlow($conn)
    {
        $query =  $conn->prepare("SELECT Distinct `dbwebsite_university`.`flow_group`.`id_flow`, `dbwebsite_university`.`flow`.`name`
        FROM `dbwebsite_university`.`flow`  inner join `dbwebsite_university`.`flow_group` on `dbwebsite_university`.`flow_group`.`id_flow` = `dbwebsite_university`.`flow`.`id`");
        
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_BOTH);
        return $result;
    }

    public function findGroupFlow($id_flow)
    {
        $query =  $this->conn->prepare("SELECT `dbwebsite_university`.`flow_group`.`id_flow`, `dbwebsite_university`.`flow`.`name`, `dbwebsite_university`.`flow_group`.`id_group`
        FROM `dbwebsite_university`.`flow`  inner join `dbwebsite_university`.`flow_group` on `dbwebsite_university`.`flow_group`.`id_flow` = `dbwebsite_university`.`flow`.`id`
        where `dbwebsite_university`.`flow_group`.`id_flow` = ?");
        $query->execute([$id_flow]);
        $result = $query->fetchall(PDO::FETCH_BOTH);

        if (count($result)>0)
        {
            for ($i=0; $i < count($result); $i++)
            {
                $this->Groups[] = new Group($this->conn, $result[$i]['id_group']);
            }

            $this->id_flow = $result[0]['id_flow']; $this->name_flow = $result[0]['name_flow'];
            return true;

        }
        else return false;

    }
}

?>
