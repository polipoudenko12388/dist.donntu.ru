<?php

include_once "../model/Authorization.php";
include_once "../model/Human_teacher.php";
include_once "../model/User_teacher.php";

class ControllerRegistration
{
    private $connUniversity;
    private $connWebsite;
    private $authorization;
    private $human_teacher;
    private $user_teacher;

    public function __construct($connUniversity, $connWebsite) 
    { 
        $this->connUniversity = $connUniversity;  
        $this->connWebsite = $connWebsite;  
        $this->authorization = new Authorization($connWebsite);  
        $this->human_teacher = new Human_teacher($connUniversity);  
        $this->user_teacher = new User_teacher($connWebsite);  
    }

    // получение массива ролей перед регистрацией
    public function getRole()
    {
        $result = $this->authorization->printRole();
        if ($result!=null)
        {
            $ArrayRole = null;
            for ($i=0; $i<count($result); $i++)
            {
                $ArrayRole[] = array("id_role"=>$result[$i]['id'], "name_role"=>$result[$i]['name']);
            }

        return $ArrayRole;
        }
        else return ["error"=>"На данный момент возможные роли для регистрации в базе отсутствуют. Ожидайте."];
    }


    public function ckeckveritif($email, $login, $password, $id_user, $id_role, $date_registration, $date_last_visit)
    {
        $verification_login = $this->authorization->verification_login($login);
        if($verification_login)  return  ["error"=>"Пользователь с данным логином уже существует. Придумайте другой."];
        else 
        {
            $verification_email = $this->authorization->verification_email($email);
            if ($verification_email)  return  ["error"=>"Пользователь с данным email уже существует. Выберите другой."];
            else 
            {
                $this->authorization->InsertAuthorizationUser($email, $login, $password, $id_user, $id_role, $date_registration, $date_last_visit);
                return ["info"=>"Регистрация прошла успешно."];
            }
        }
    }
    public function funRegistrationCheck($id_role, $name_role, $snils_inn, $login, $email, $password, $date_registration, $date_last_visit)
    {
        if (strcasecmp($name_role, "преподаватель") == 0)
        {
            $result = $this->human_teacher->find_teacher_dbubiversity($snils_inn);

            // человек есть в БД как преподаватель
            if ($result['info']==1)
            {
                // проверка, есть ли человек уже в бд сайта (вдруг заново хочет зарегистрироваться)
                $checkTableUser = $this->user_teacher->find_teacher_dbwebsite($snils_inn);

                if ($checkTableUser['info']==0) return  ["error"=>"Вы уже зарегистрированы."];
                // его нет в БД в целом
                else if ($checkTableUser['info']==1)
                {
                    $this->user_teacher->InsertUser($this->human_teacher->surname, $this->human_teacher->name, $this->human_teacher->patronymic, $this->human_teacher->email,
                    $this->human_teacher->phone, $this->human_teacher->photo, $this->human_teacher->snils, $this->human_teacher->inn);

                    $id_user = $this->user_teacher->find_human_dbwebsite($snils_inn)['id'];
                    $this->user_teacher->InsertTeacher($id_user, $this->human_teacher->name_position);

                    return $this->ckeckveritif($email, $login, $password, $id_user, $id_role, $date_registration, $date_last_visit);

                }
                // человека нет в таблице преподаватели
                else if ($checkTableUser['info']==2)
                {
                    $id_user = $this->user_teacher->find_human_dbwebsite($snils_inn)['id'];
                    $this->user_teacher->InsertTeacher($id_user, $this->human_teacher->name_position);

                    return $this->ckeckveritif($email, $login, $password, $id_user, $id_role, $date_registration, $date_last_visit);
                }

            }
            else if ($result['info']==0)  return  ["error"=>"Вас нет в БД Университета. Регистрация невозможна."];
            else if ($result['info']==2)  return  ["error"=>"Вас нет в БД Университета как преподавателя. Регистрация невозможна."];
        }
        
    }


    
}
?>