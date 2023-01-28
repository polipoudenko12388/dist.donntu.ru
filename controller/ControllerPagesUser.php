<?php

include_once "../model/Authorization.php";
include_once "../model/User_teacher.php";
include_once "../model/Discipline_flow.php";
include_once "../controller/ControllerAuthorization.php";
include_once "../model/Human_teacher.php";

class ControllerPagesUser
{
    private $connUniversity;
    private $connWebsite;
    private $authorization;
    private $user_teacher;
    private $discipline_flow;
    private $human_teacher;

    public function __construct($connWebsite, $connUniversity) 
    { 
        $this->connUniversity = $connUniversity;  
        $this->connWebsite = $connWebsite;  
        $this->authorization = new Authorization($connWebsite);  
        $this->user_teacher = new User_teacher($connWebsite);   
        $this->discipline_flow = new Discipline_flow($connWebsite);  
        $this->human_teacher = new Human_teacher($connUniversity);
    }

       // если переход производится на "главную преподавателя"
       public function mainUser($token,$id_user)
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

       public function profileUser($token,$id_user)
       {
        // по id_user узнать его роль и токен
        $result = $this->authorization->print_user($id_user);

            // если данный пользователь есть в БД, проверяем, авторизован он или нет
        if ($result)
        {
            // если авторизован (токены совпадают)
            if (strcasecmp($token, $result['token']) == 0)
            {
                // если преподаватель, то из Human_teacher достать и отправить нужные данные в профиль
                if ($result['id_role']==2)
                {
                    $date_registration = $result['date_registration'];
                    $date_last_visit = $result['date_last_visit'];

                    unset($result);

                    // по id_user узнать его СНИЛС/ИНН
                    $this->user_teacher->printfInfoTeacher($id_user); 
                    
                    if ($this->user_teacher->inn==null) $snils_inn =  $this->user_teacher->snils;
                    else $snils_inn =  $this->user_teacher->inn;

                    if ($this->user_teacher->rights_admin==1) $rights_admin =  'присутствуют';
                    else $rights_admin =  'отсутствуют';

                    $result = $this->human_teacher->find_teacher_dbubiversity($snils_inn);
                    // человек есть в БД как преподаватель
                    if ($result['info']==1)
                    {
                            $infoTeacher = array("FIO"=>$this->human_teacher->surname . " ".$this->human_teacher->name." ".$this->human_teacher->patronymic,
                        "rights_admin"=>$rights_admin,  "date_registration"=>$date_registration, "date_last_visit"=>$date_last_visit,
                        "country"=>$this->human_teacher->name_country, "name_regions"=>$this->human_teacher->name_regions, "name_type"=>$this->human_teacher->name_type,
                        "name_settlements"=>$this->human_teacher->name_settlements, "email"=>$this->human_teacher->email, "phone"=>$this->human_teacher->phone, 
                        "institute"=>$this->human_teacher->name_institute, "faculty"=>$this->human_teacher->name_faculty, "department"=>$this->human_teacher->name_department,
                        "status"=>$this->human_teacher->name_status);

                        return $infoTeacher;
                    }
                    else return ["error"=>"Вас нет в бд университета как преподавателя."];
                }
                else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."];
        }
}
?>