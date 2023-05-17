<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Human;
use App\Models\HumanWorkes;
use App\Models\User;
use App\Models\Registration;

class RegistrationController extends Controller
{
    private $human;
    private $humanWorkes;

    public function __construct()
    {
        $this->human=new Human();
        $this->humanWorkes = new HumanWorkes();
    }

    // обработка регистрации
    function RegistrationProcessing(Request $request)
    {

        if ($request->input('id_role_user') == 4) return ["error"=>"На данный момент регистрация для сотрудников невозможна. Ожидайте обновлений."];
        else
        {
            // поиск человека по снилс/инн в т. Human
            $id_human = $this->human->SearchPersonbyInnSnils($request->input('inn_snils'));
            if (empty($id_human)) return response()->json(["error"=>"Ошибка. Данный человек отсутствует в базе ВУЗа. Регистрация невозможна!"]);
            else  // человек есть в т. Human
            {
                if ($request->input('id_role_user') == 5) // преподаватель
                {
                    $Array_id_human_position_teacher = $this->humanWorkes->SearchWorkes($id_human->id, $request->input('id_role_db_univ'));
                    if (count($Array_id_human_position_teacher)==0) return response()->json(["error"=>"Ошибка. Данный человек отсутствует в базе ВУЗа под выбранной ролью. Регистрация невозможна!"]);
                    else // человек есть в т. Workes
                    {
                        // проверка наличия преподавателя в т. position_teacher;  map=foreach
                        $countrecording_position_teacher = User::getCountUserTeacherorStudent($Array_id_human_position_teacher->map(function ($object){ return $object->id; }), "position_teacher","id_workes_db_univ");
                        if ($countrecording_position_teacher>0) return response()->json(["error"=>"Ошибка. Пользователь с данным снилсом/инн уже зарегистрирован!"]);
                        else
                        {
                            // проверка введенного email/login
                            $countrecording_verif_user = Registration::VerificationEmailLogin($request->input('email'), $request->input('login'));

                            if ($countrecording_verif_user>0) return response()->json(["error"=>"Ошибка. Пользователь с данным email/login уже зарегистрирован. Придумайте новый email/login!"]);
                            else 
                            {
                                // проверка наличия человека в т. User
                                $id_user = User::SearchRecordbyId("user", "id","id_human_db_univ", $id_human->id);
                                if (empty($id_user)) // отсутствует
                                {
                                    $dataHuman = $this->human->getDataHuman($id_human->id);
                                    //добавление в т. User
                                    $id_user = User::getIdInsertRecord(array("surname" => $dataHuman->surname, "name"=> $dataHuman->name, "patronymic" => $dataHuman->patronymic, 
                                    "email" => $dataHuman->email, "phone" => $dataHuman->phone, "id_human_db_univ" => $dataHuman->id), "user");
                                }
                                else $id_user=$id_user->id;

                                // добавление в т. teacher/registration/position_teacher
                                $id_teacher=User::getIdInsertRecord(array("id_user" => $id_user), "teacher");

                                $date = date("Y-m-d", strtotime($request->input('date_registration')));
                                User::InsertRecord(array("id_user" => $id_user, "id_role_user"=>$request->input('id_role_user'), "email" => $request->input('email'),
                                "login"=>$request->input('login'), "password"=>$request->input('password'), "date_registration"=> $date), "registration");
                             
                                User::InsertRecord(array_map(function ($object)  use (&$id_teacher) 
                                { return array("id_teacher_x" => $id_teacher, "id_workes_db_univ" => $object->id); }, 
                                $Array_id_human_position_teacher->toArray()), "position_teacher");
                                return response()->json(["info"=>"Регистрация прошла успешно!"]);
                            }
                        }
                    }
                }
                else if ($request->input('id_role_user') == 1) // студент
                {
                    return "Это студент";
                }
            }
        }

    }

    // получение списка ролей
    function getListRole()
    {
        $ListRole = User::getListData(User::$ConnectDBWebsite, "role_user", ['id as id_role_user','name','id_role_db_univ']);
        if (count($ListRole)==0)  return response()->json(["error"=>"На данный момент возможные роли для регистрации в базе отсутствуют. Ожидайте обновлений."]);
       else return response()->json($ListRole); 
    }
}
