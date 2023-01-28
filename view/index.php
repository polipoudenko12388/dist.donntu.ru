<?

include_once "../controller/ControllerPagesUser.php";
include_once "../api/DBConnection.php";

// Получаем соединение с базой данных
$databaseuniversity = new DateBaseConnection("localhost", "dbuniversity","root", "0000000");
$dbuniversity = $databaseuniversity->connection;

$databasewebsite = new DateBaseConnection("localhost", "dbwebsite_university","root", "0000000");
$website = $databasewebsite->connection;


// необходимые HTTP заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once "../controller/ControllerRegistration.php";
include_once "../controller/ControllerAuthorization.php";
include_once "../controller/ControllerPagesUser.php";
include_once "../controller/ControllerDisciplineUser.php";


if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $data = json_decode(file_get_contents('php://input'), true);
    $controllerRegistration = new ControllerRegistration($dbuniversity, $website);
    $controllerAuthorization = new ControllerAuthorization($website);
    $controllerPagesUser = new ControllerPagesUser($website, $dbuniversity);
    $controllerDisciplineUser = new ControllerDisciplineUser($website);

    // нажата кнопка на главной "регистрация" (формат отправки на сервер: {"getRolebeforeAuthor":{"getRolebeforeAuthor":"true"}}
    if ($data["getRolebeforeAuthor"]) $result = $controllerRegistration->getRole(); 

    // нажата кнопка "зарегистрироваться", формат отправки на сервер: ({"RegistrationCkeck": {"id_role":"1","name_role":"преподаватель", "snils_inn":"121212121234", "login":"hhhhh", "email":"jjjj",  "password":"hjhjh", "date_registration":"2022-11-11" }})
    else if ($data["RegistrationCkeck"])
    {
        $RegistrationCkeck = $data["RegistrationCkeck"]; 
        $result = $controllerRegistration->funRegistrationCheck($RegistrationCkeck['id_role'], $RegistrationCkeck['name_role'],
        $RegistrationCkeck['snils_inn'], $RegistrationCkeck['login'], $RegistrationCkeck['email'], $RegistrationCkeck['password'], 
        $RegistrationCkeck['date_registration'], null);
    }

    // нажата кнопка "войти(после ввода логина и пароля)", формат отправки на сервер: {"Input":{"email_login":"logintwo","password":"1122hjhjh"}}
    else if ($data["Input"])
    {
        $input = $data["Input"]; 
        $result = $controllerAuthorization->veritifLoginPassword($input['email_login'], $input['password']);
    }
    // если переход происходит на "Главную пользователя из других страниц" : {"MainTeacher":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2,"id_teacher":6}}
    else if ($data["MainTeacher"])
     {
        $mainTeacher = $data["MainTeacher"]; 
        $result =  $controllerPagesUser->mainUser($mainTeacher['token'],$mainTeacher['id_user']);
     }

     // если переход происходит на "Профиль пользователя из других страниц" : {"ProfileTeacher":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2,"id_teacher":6}}
     else if ($data["ProfileTeacher"])
     {
        $profileTeacher = $data["ProfileTeacher"]; 
        $result =  $controllerPagesUser->profileUser($profileTeacher['token'],$profileTeacher['id_user']);
     }

     // если переход происходит на "Все дисциплины пользователя из других страниц" : {"AllDisciplineTeacher":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2,"id_teacher":6}}
     else if ($data["AllDisciplineTeacher"]) 
     {
        $allDisciplineTeacher = $data["AllDisciplineTeacher"]; 
        $result =  $controllerDisciplineUser->AllDisciplineUser($allDisciplineTeacher['token'],$allDisciplineTeacher['id_user']);
     }

     // при нажатии на кнопку "создать дисциплину" {"ButtonClickaddNewDiscipline":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2,"id_teacher":6}}
     else if ($data["ButtonClickaddNewDiscipline"]) 
     {
        $buttonClickaddNewDiscipline = $data["ButtonClickaddNewDiscipline"]; 
        $result =  $controllerDisciplineUser->buttonaddNewDiscipline($buttonClickaddNewDiscipline['token'],$buttonClickaddNewDiscipline['id_user']);
     }

     // {"ButtonClicksaveNewDiscipline":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2, "id_teacher":1, "name":"new_discipline", "id_institute":6, "id_faculty":17, "id_department":3, "fon":null}}
     else if ($data["ButtonClicksaveNewDiscipline"]) 
     {
        $buttonClicksaveNewDiscipline = $data["ButtonClicksaveNewDiscipline"]; 
        $result =  $controllerDisciplineUser->saveNewDiscipline($buttonClicksaveNewDiscipline['token'],$buttonClicksaveNewDiscipline['id_user'], 
        $buttonClicksaveNewDiscipline['name'], $buttonClicksaveNewDiscipline['id_institute'],$buttonClicksaveNewDiscipline['id_faculty'], 
        $buttonClicksaveNewDiscipline['id_department'], $buttonClicksaveNewDiscipline['id_teacher'], $buttonClicksaveNewDiscipline['fon']);
     }

    //  {"ButtonClickEditDiscipline":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":7,"id_teacher":3,"id_discipline":1}}
     else if ($data["ButtonClickEditDiscipline"]) 
     {
        $buttonClickEditDiscipline = $data["ButtonClickEditDiscipline"]; 
        $result =  $controllerDisciplineUser->buttonEditDiscipline($buttonClickEditDiscipline['token'],$buttonClickEditDiscipline['id_user'], 
        $buttonClickEditDiscipline['id_discipline'], $buttonClickEditDiscipline['id_teacher']);
     }

    //  {"ButtonClicksaveEditDiscipline":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2,"id_teacher":1,"name":"dissdsdsdsdcipline","id_institute":6,"id_faculty":17,"id_department":3,"fon":null,"id_discipline":1}}
     else if ($data["ButtonClicksaveEditDiscipline"]) 
     {
        $buttonClicksaveEditDiscipline = $data["ButtonClicksaveEditDiscipline"]; 
        $result =  $controllerDisciplineUser->saveEditDiscipline($buttonClicksaveEditDiscipline['token'],$buttonClicksaveEditDiscipline['id_user'], 
        $buttonClicksaveEditDiscipline['name'], $buttonClicksaveEditDiscipline['id_institute'],$buttonClicksaveEditDiscipline['id_faculty'], 
        $buttonClicksaveEditDiscipline['id_department'], $buttonClicksaveEditDiscipline['id_teacher'], $buttonClicksaveEditDiscipline['fon'], $buttonClicksaveEditDiscipline['id_discipline']);
     }

    //  {"ButtonClickaddNewFlowDiscipline":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2,"id_teacher":1,"id_discipline":1}}
     else if ($data["ButtonClickaddNewFlowDiscipline"]) 
     {
        $buttonClickaddNewFlowDiscipline = $data["ButtonClickaddNewFlowDiscipline"]; 
        $result =  $controllerDisciplineUser->buttonaddNewFlow($buttonClickaddNewFlowDiscipline['token'],$buttonClickaddNewFlowDiscipline['id_user'],
        $buttonClickaddNewFlowDiscipline['id_discipline'], $buttonClickaddNewFlowDiscipline['id_teacher']);
     }

    //  {"ButtonClickSaveNewFlowDiscipline":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2,"id_teacher_ovner":1,"id_discipline":1,"number_hours_reading":130,"id_flow":2,"array_idteacher":{"id":[1,3]}}}
     else if ($data["ButtonClickSaveNewFlowDiscipline"])
     {
        $buttonClickSaveNewFlowDiscipline = $data["ButtonClickSaveNewFlowDiscipline"]; 
        $result =  $controllerDisciplineUser-> saveSaveFlowDiscipline($buttonClickSaveNewFlowDiscipline['token'],$buttonClickSaveNewFlowDiscipline['id_user'], 
        $buttonClickSaveNewFlowDiscipline['number_hours_reading'], $buttonClickSaveNewFlowDiscipline['id_flow'], $buttonClickSaveNewFlowDiscipline['id_teacher_ovner'],
        $buttonClickSaveNewFlowDiscipline['id_discipline'],  $buttonClickSaveNewFlowDiscipline['array_idteacher']['id']);
     }

    //  {"ButtonExit":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF90ZWFjaGVyIjoxfQ.BkPnWnRd3VHuD1y254Jy9WnQtZmuZEzkI2u6o_6v9wI","id_user":2}}
     else if($data["ButtonExit"])
     {
        $buttonExit = $data["ButtonExit"]; 
        $result =  $controllerAuthorization->exit($buttonExit['id_user'], $buttonExit['token']);
     }

    else $result = array("error" => "Запрос не принят в обработку!"); 
}
else $result = array("error" => "Запрос не принят в обработку!"); 
echo json_encode($result);
?>
