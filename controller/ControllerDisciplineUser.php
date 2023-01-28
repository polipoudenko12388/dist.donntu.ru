<?php

include_once "../model/Authorization.php";
include_once "../model/User_teacher.php";
include_once "../model/Discipline_flow.php";
include_once "../model/Discipline.php";
include_once "../model/Flow.php";
include_once "../controller/ControllerAuthorization.php";


class ControllerDisciplineUser
{
    private $connWebsite;
    private $authorization;
    private $user_teacher;
    private $discipline_flow;
    private $discipline;
    private $unstitute;
    private $faculty;
    private $department;

    public function __construct($connWebsite) 
    { 
        $this->connWebsite = $connWebsite;  
        $this->authorization = new Authorization($connWebsite);  
        $this->user_teacher = new User_teacher($connWebsite);   
        $this->discipline_flow = new Discipline_flow($connWebsite);  
        $this->discipline = new Discipline($connWebsite);  

        $this->unstitute = $this->discipline->printfDirectory('`dbwebsite_university`.`institute`');
        $this->faculty = $this->discipline->printfDirectory('`dbwebsite_university`.`faculty`');
        $this->department = $this->discipline->printfDirectory('`dbwebsite_university`.`department`');
    }

    public function AllDisciplineUser($token,$id_user)
    {
        $result = $this->authorization->print_user($id_user);
   
        // если данный пользователь есть в БД, проверяем, авторизован он или нет
        if ($result)
        {
            // если авторизован (токены совпадают)
            if (strcasecmp($token, $result['token']) == 0)
            {
                if ($result['id_role']==2)
                {
                    unset($result);
                    $this->user_teacher->printfInfoTeacher($id_user); 
                    $arrayInput = ControllerAuthorization::funInfoteacherMainDiscipline($id_user,  $this->user_teacher->id_teacher, $token,$this->user_teacher->surname, $this->user_teacher->name, 
                    $this->user_teacher->patronymic, $this->discipline_flow);
                    return $arrayInput;

                }
                else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."];
    }

    public function saveNewDiscipline($token,$id_user, $name, $id_institute,$id_faculty, $id_department, $id_teacher, $fon)
    {
        $result = $this->authorization->print_user($id_user);
   
       // если данный пользователь есть в БД, проверяем, авторизован он или нет
       if ($result)
       {
           // если авторизован (токены совпадают)
           if (strcasecmp($token, $result['token']) == 0)
           {
                if ($result['id_role']==2)
                {
                    unset($result);
                    $result = $this->discipline->InsertDiscipline($name, $id_institute,$id_faculty, $id_department, $id_teacher, $fon);

                    if ($result) return ["info"=>"Дисциплина успешно создана."];
                    else return ["info"=>"Дисциплина не была создана."];
                }
                else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."];
    }

    public function listInstDepartFaculty($catalogarray)
    {
        for ($i=0; $i<count($this->unstitute); $i++) { $catalogarray[0]['listinstitute'][$i] = array("id_unstitute"=>$this->unstitute[$i]['id'], "name"=>$this->unstitute[$i]['name']); }
        for ($i=0; $i<count($this->faculty); $i++) { $catalogarray[0]['listfaculty'][$i] = array("id_faculty"=>$this->faculty[$i]['id'], "name"=>$this->faculty[$i]['name']); }
        for ($i=0; $i<count($this->department); $i++) { $catalogarray[0]['listdepartment'][$i] = array("id_department"=>$this->department[$i]['id'], "name"=>$this->department[$i]['name']); }

        return $catalogarray;
    }
    // преподаватель нажимает на кнопку "создать новую дисциплину"
    public function buttonaddNewDiscipline($token,$id_user)
    {
        $result = $this->authorization->print_user($id_user);
   
       // если данный пользователь есть в БД, проверяем, авторизован он или нет
       if ($result)
       {
           // если авторизован (токены совпадают)
           if (strcasecmp($token, $result['token']) == 0)
           {
                if ($result['id_role']==2)
                {
                    $catalogarray = array();
                    return $this->listInstDepartFaculty($catalogarray);
                }
                else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."];
    }

    public function buttonEditDiscipline($token,$id_user, $id_discipline, $id_teacher)
    {
        $result = $this->authorization->print_user($id_user);
   
       // если данный пользователь есть в БД, проверяем, авторизован он или нет
       if ($result)
       {
           // если авторизован (токены совпадают)
           if (strcasecmp($token, $result['token']) == 0)
           {
                if ($result['id_role']==2)
                {
                    unset($result);

                    $result = $this->discipline->printfDisciplineId($id_discipline, $id_teacher);
                    if ($result) 
                    {
                        $listEditDisc = array("name"=>$this->discipline->name_discipline, "id_institute"=>$this->discipline->id_institute,
                    "id_faculty"=>$this->discipline->id_faculty, "id_department"=>$this->discipline->id_department, 
                    "id_teacher_ovner"=>$this->discipline->id_teacher_ovner, "fon"=>$this->discipline->fon);

                    return $this->listInstDepartFaculty($listEditDisc);

                    }
                    else return ["error"=>"Вы не являетесь владельцем данной дисциплины.Редактирование невозможно."];
                }
                else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."];
    }

    public function saveEditDiscipline($token,$id_user, $name, $id_institute,$id_faculty, $id_department, $id_teacher, $fon, $id_discipline)
    {
        $result = $this->authorization->print_user($id_user);
   
       // если данный пользователь есть в БД, проверяем, авторизован он или нет
       if ($result)
       {
           // если авторизован (токены совпадают)
           if (strcasecmp($token, $result['token']) == 0)
           {
                if ($result['id_role']==2)
                {
                    unset($result);
                    $result = $this->discipline->UpdateDisciplineId($name, $id_institute,$id_faculty, $id_department, $id_teacher, $fon, $id_discipline);

                    if ($result) return ["info"=>"Дисциплина была успешно отредактирована."];
                    else return ["error"=>"Дисциплина не была отредактирована. Ошибка!"];

                }
                else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."];
        
    }

    // преподаватель нажимает на кнопку "добавить поток к дисциплине"
    public function buttonaddNewFlow($token,$id_user, $id_discipline, $id_teacher)
    {
        $result = $this->authorization->print_user($id_user);
   
       // если данный пользователь есть в БД, проверяем, авторизован он или нет
       if ($result)
       {
           // если авторизован (токены совпадают)
           if (strcasecmp($token, $result['token']) == 0)
           {
                if ($result['id_role']==2)
                {
                    $result = Discipline::TestdelOrEdit($id_discipline, $id_teacher, $this->connWebsite);
                    if ($result) 
                    {
                        unset($result);
                        $listflows = Flow::printfFlow($this->connWebsite);
                        $listteacher = $this->user_teacher->printfTeacher();

                        $result = array();
                        for ($i=0; $i<count($listflows); $i++) { $result[0]['listflows'][$i] = array("id_flow"=>$listflows[$i]['id_flow'], "name"=>$listflows[$i]['name']); }
                        for ($i=0; $i<count($listteacher); $i++) { $result[0]['listteacher'][$i] = array("id_teacher"=>$listteacher[$i]['id_teacher'], 
                            "FIO"=> ($listteacher[$i]['surname']." ".$listteacher[$i]['name']." ".$listteacher[$i]['patronymic'])); }

                        return $result;
                    }
                    else return ["error"=>"Вы не являетесь владельцем данной дисциплины.Добавление потоков невозможно."];
                }
                else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."];
    }

    public function saveSaveFlowDiscipline($token,$id_user, $number_hours_reading, $id_flow, $id_teacher_ovner, $id_discipline,  $array_idteacher)
    {
        $result = $this->authorization->print_user($id_user);
   
       // если данный пользователь есть в БД, проверяем, авторизован он или нет
       if ($result)
       {
           // если авторизован (токены совпадают)
           if (strcasecmp($token, $result['token']) == 0)
           {
                if ($result['id_role']==2)
                {
                    unset($result);
                    $result = $this->discipline_flow->InsertTeacheronFlowDiscipline($id_discipline, $number_hours_reading, $id_flow, $id_teacher_ovner, $array_idteacher);
                    return $result;
                }
                else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."];
        
    }

}
?>