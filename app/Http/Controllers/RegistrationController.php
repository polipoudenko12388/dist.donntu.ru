<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Human;
use App\Models\HumanStudent;
use App\Models\HumanWorkes;
use App\Models\User;
use App\Models\Registration;
use App\Models\Log;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    private $human;
    private $humanWorkes;
    private $humanStudents;

    public function __construct()
    {
        $this->human=new Human();
        $this->humanWorkes = new HumanWorkes();
        $this->humanStudents = new HumanStudent();
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
                        return response()->json($this->user_verification($Array_id_human_position_teacher, $id_human, "position_teacher","id_workes_db_univ",$request, "teacher"));  
                    }
                }
                else if ($request->input('id_role_user') == 1) // студент
                {
                    $Array_id_study_human_student = $this->humanStudents->SearchStudents($id_human->id); // +++
                   
                    if (count($Array_id_study_human_student)==0) return response()->json(["error"=>"Ошибка. Данный человек отсутствует в базе ВУЗа под выбранной ролью. Регистрация невозможна!"]);
                    else // человек есть в т. Student
                    {
                        return response()->json($this->user_verification($Array_id_study_human_student, $id_human, "group_student","id_student_db_univ",$request, "student"));  
                    }
                }
            }
        }

    }

    private function user_verification($Array_id_human_student_workes, $id_human, $nametable_check_count_rec, $namecolumn_check_count_rec,$request, $nametable_stud_teach)
    {       
        if (count($Array_id_human_student_workes)==0) return ["error"=>"Ошибка. Данный человек отсутствует в базе ВУЗа под выбранной ролью. Регистрация невозможна!"];
        else // человек есть в т. Student or Workes
        {
            // проверка наличия студента в т. group_student / преподавателя в т. position_teacher
            $countrecording = User::getCountUserTeacherorStudent($Array_id_human_student_workes->map(function ($object){ return $object->id; }), $nametable_check_count_rec,$namecolumn_check_count_rec);
            if ($countrecording>0) return ["error"=>"Ошибка. Пользователь с данным снилсом/инн уже зарегистрирован!"];
            else
            {
                // проверка введенного email/login
                $countrecording_verif_user = Registration::VerificationEmailLogin($request->input('email'), $request->input('login'));
                if ($countrecording_verif_user>0) return ["error"=>"Ошибка. Пользователь с данным email/login уже зарегистрирован. Придумайте новый email/login!"];
                else 
                {
                    // проверка наличия человека в т. User
                    $id_user = User::SearchRecordbyId("user", "id","id_human_db_univ", $id_human->id);
                    $dataHuman = $this->human->getDataHuman($id_human->id);
                    if (empty($id_user)) // отсутствует
                    {
                        //добавление в т. User
                        $id_user = User::getIdInsertRecord(array("surname" => $dataHuman->surname, "name"=> $dataHuman->name, "patronymic" => $dataHuman->patronymic, 
                        "email" => $dataHuman->email, "phone" => $dataHuman->phone, "id_human_db_univ" => $dataHuman->id), "user");
                    }
                    else $id_user=$id_user->id;

                    // добавление в т. student/registration/group_student / teacher/registration/position_teacher
                    $id_student_teacher=User::getIdInsertRecord(array("id_user" => $id_user), $nametable_stud_teach);

                    $date = date("Y-m-d", strtotime($request->input('date_registration')));
                    User::InsertRecord(array("id_user" => $id_user, "id_role_user"=>$request->input('id_role_user'), "email" => $request->input('email'),
                    "login"=>$request->input('login'), "password"=>$request->input('password'), "date_registration"=> $date), "registration");
                       
                    if ($request->input('id_role_user') == 1)
                    {
                        User::InsertRecord(array_map(function ($object)  use (&$id_student_teacher) 
                        { return array("id_student" => $id_student_teacher, "id_group" =>User::SearchRecordbyId("group", "id", "id_group_db_univ", $object->id_group)->id, "id_student_db_univ" => $object->id); },  
                        $Array_id_human_student_workes->toArray()), "group_student");

                        // определяю группы, в которые входит студент
                        $array_group_student = array_map(function ($object){ return array("id_group" =>User::SearchRecordbyId("group", "id", "id_group_db_univ", $object->id_group)->id); },  
                        $Array_id_human_student_workes->toArray());
                        // в какие потоки входят эти группы
                        $buf_flow = array_map(function ($object){ return User::SeachRecordsbyWhere("flow_group", "id_group=?", [$object['id_group']]); },$array_group_student);
                        
                        if (count($buf_flow)>0)
                        {
                            foreach ($buf_flow as $item)  { foreach ($item as $item2) {$array_flow_group_student[] = $item2; } }
                            // проверяю какие потоки добавлены в дисциплины 
                            $array_id_log_group=null;
                            foreach ($array_flow_group_student as $item)
                            { 
                                foreach (Log::SearchGroupsFlowinLog(['log_group.id as id_log_group','log_disc_flow.id_type_log','log_group.log_group_json'],$item->id_flow,[$item->id_group],null) as $item2)
                                {
                                  $array_id_log_group[]=$item2;
                                }
                            }

                            if (count($array_id_log_group)>0)
                            {
                                $type_execution = User::SearchRecordbyId("type_execution", ['name'], 'id', 1)->name;
                                // по id_log_group редактируем журналы
                                // цикл по журналам
                                for ($i=0;$i<count($array_id_log_group);$i++)
                                {
                                    if ($array_id_log_group[$i]->id_type_log==1) // журнал посещаемости
                                    {
                                        $this->updatejson($array_id_log_group[$i]->id_log_group,$id_student_teacher,$dataHuman,
                                        '$.attendance_group',"'presence_class','-'");
                                    }
                                    else if ($array_id_log_group[$i]->id_type_log==2) // журнал успеваемости
                                    {
                                        $this->updatejson($array_id_log_group[$i]->id_log_group,$id_student_teacher,$dataHuman,
                                        '$.tasks',"'id_type_execution',1,'type_execution','".$type_execution."','score',null,'date',null",true);

                                        $this->updatejson($array_id_log_group[$i]->id_log_group,$id_student_teacher,$dataHuman,
                                        '$.other_types_control',"'score',null,'date',null");

                                        $this->updatejson($array_id_log_group[$i]->id_log_group,$id_student_teacher,$dataHuman,
                                        '$.Control_educational_process.intersessional_control',"'passage',null,'date',null");

                                        $this->updatejson($array_id_log_group[$i]->id_log_group,$id_student_teacher,$dataHuman,
                                        '$.Control_educational_process.passes',"'count',null,'date',null");
                                    
                                        $this->updatejson($array_id_log_group[$i]->id_log_group,$id_student_teacher,$dataHuman,
                                        '$.Results_control_educational_process.offset',"'score',null,'date',null");

                                        $this->updatejson($array_id_log_group[$i]->id_log_group,$id_student_teacher,$dataHuman,
                                        '$.Results_control_educational_process.exam',"'score',null,'date',null");
                                    }
                                }
                            }
                        }
                    }
                    else if ($request->input('id_role_user') == 5)
                    {
                        User::InsertRecord(array_map(function ($object)  use (&$id_student_teacher) 
                        {return array("id_teacher_x" => $id_student_teacher, "id_workes_db_univ" => $object->id);  },  
                        $Array_id_human_student_workes->toArray()), "position_teacher");
                    }
                    return ["info"=>"Регистрация прошла успешно!"];
                }
            }
        }    
    }

    public static function updatejson($id_log_group,$id_student_teacher,$dataHuman,$namecolumnjsonlength,$str_json_object_append,$flag=false)
    {
        
        $log = User::SeachRecordsbyWhere("log_group", "id=?", [$id_log_group],
        "JSON_LENGTH(log_group_json,'".$namecolumnjsonlength."') as count, log_group_json");
        for ($j=0;$j<$log[0]->count;$j++)
        {
            $bufdatathuman = "'id_student',".$id_student_teacher.",'name','".$dataHuman->name."','surname','".$dataHuman->surname."', 
            'patronymic','".$dataHuman->patronymic."',";
            if ($flag==true)
            {
                $buf = json_decode($log[0]->log_group_json);
                $foldertaskgroup = $buf->tasks[$j]->folder;
                // создание папки
                $folder_files_student = $foldertaskgroup."/id".$id_student_teacher."".File::translit($dataHuman->surname);
                Storage::disk('mypublicdisk')->makeDirectory($folder_files_student);

                User::UpdateColumnJson("log_group", "id=?", [$id_log_group], 
                "log_group_json","JSON_ARRAY_APPEND(`log_group_json`, '".$namecolumnjsonlength."[".$j."].array_students', 
                json_object(".$bufdatathuman.$str_json_object_append.",'folder','".$folder_files_student."'))");
            }
            else
            {
                User::UpdateColumnJson("log_group", "id=?", [$id_log_group], 
                "log_group_json","JSON_ARRAY_APPEND(`log_group_json`, '".$namecolumnjsonlength."[".$j."].array_students', 
                json_object(".$bufdatathuman.$str_json_object_append."))");
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
