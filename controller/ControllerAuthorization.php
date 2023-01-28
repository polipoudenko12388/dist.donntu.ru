<?php

use ReallySimpleJWT\Token;

include_once "../model/Authorization.php";
include_once "../model/User_teacher.php";
include_once "../model/Discipline_flow.php";

class ControllerAuthorization
{
    private $connWebsite;
    private $authorization;
    private $user_teacher;
    private $discipline_flow;

    public function __construct($connWebsite) 
    { 
        $this->connWebsite = $connWebsite;  
        $this->authorization = new Authorization($connWebsite);  
        $this->user_teacher = new User_teacher($connWebsite);  
        $this->discipline_flow = new Discipline_flow($connWebsite);  
    }

    public function createToken($id_teacher)
    {
        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        // Create token payload as a JSON string
        $payload = json_encode(['id_teacher' => $id_teacher]);
        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, date_timestamp_get(date_create()), true);
        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }

 
    public function exit($id_user, $token)
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
                    $this->authorization->UpdateToken(null, $id_user);
                    return ["info"=>"Выход совершен."];
                }
            }
            else return ["error"=>"Вы не авторизованы."];
        }
        else return ["error"=>"Вас не найдено в системе."]; 
    }

    
    public static function funInfoteacherMainDiscipline($id_user, $id_teacher, $token,$surname, $name, $patronymic, $discipline_flow)
    {
        $arrayInput = array("id_user"=>$id_user, "id_teacher" => $id_teacher, "token"=>$token,  "FIO"=> ($surname. " ".$name." ".$patronymic));
        if ($discipline_flow->printDisciplineonTeacher($id_teacher)) $arrayInput[0]["arraydiscipline"]=$discipline_flow->printDisciplineonTeacher($id_teacher);
        else $arrayInput[0]["arraydiscipline"]=null;
        return $arrayInput;
    }
    public function veritifLoginPassword($email_login, $password)
    {
        $result = $this->authorization->find_user($email_login, $password);

        // если данные введены правильно для авторизации
        if ($result)
        {
            if ($this->authorization->id_role==2)
            {
                $id_user = $this->authorization->id_user;
                $this->user_teacher->printfInfoTeacher($id_user); 
                $token = $this->createToken($this->user_teacher->id_teacher); // создаю токен для верификации пользователя, когда он мне будет запросы отправлять будучи в системе
            
                // обновляю таблицу авторизации (наличие токена означает, что человек в системе)
                $this->authorization->UpdateToken($token, $id_user);

                $arrayInput = $this->funInfoteacherMainDiscipline($id_user,  $this->user_teacher->id_teacher, $token,$this->user_teacher->surname, $this->user_teacher->name, 
                $this->user_teacher->patronymic, $this->discipline_flow);
                return $arrayInput;
            }
            else return ["error"=>"Вход для не преподавателей в систему пока недоступен. Ожидайте."];
        } 
        else return ["error"=>"Email(логин) или пароль неверные. Введите их заново."];
    }
}
?>