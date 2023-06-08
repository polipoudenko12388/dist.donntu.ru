<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Log;

class LogController extends Controller
{
    public function ListTypeLogs(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else {  return response()->json(User::getListData(User::$ConnectDBWebsite,'type_log', ['id as id_type','name as name_type'])); } 
    }

    public function ListTypeClass(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else {  return response()->json(User::getListData(User::$ConnectDBWebsite,'type_class', ['id as id_type','short_name as name_type'])); } 
    }

    public function getLog(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            $id_disc_flow = User::SeachRecordsbyWhere("discipline_flow", "id_list_discipline=? and id_flow=?", [$request->input('id_disc'), $request->input('id_flow')],"id");
            
            if ($id_disc_flow==null) return response()->json(["error"=>"Ошибка"]);
            else
            {
                $id_log_disc_flow = User::SeachRecordsbyWhere("log_disc_flow", "id_disc_flow=? and id_type_log=?", [$id_disc_flow[0]->id, $request->input('id_type')],"id")[0]->id;
                if ( $id_log_disc_flow!=null)
                {
                    $log_group = User::SeachRecordsbyWhere("log_group", "id_log=? and id_group=?", [$id_log_disc_flow, $request->input('id_group')],"id,id_teacher_edit,log_group_json");
                    if (count($log_group)==0) return response()->json(["error"=>"Ошибка"]);
                    else return response()->json(["id_log_group"=>$log_group[0]->id,"id_teacher_edit"=>$log_group[0]->id_teacher_edit, "log"=>json_decode($log_group[0]->log_group_json)]);
                }    
            }
        } 

    }

    public function ResultUpdateLog(Request $request)
    {
         // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            User::UpdateColumn("log_group",['id','=',$request->input('id_log_group')], ["log_group_json"=>$request->input('log'),"id_teacher_edit"=>null]); 
            return response()->json(["info"=>"Обновление журнала прошло успешно."]);
        } 
    }

    public function ResultPermitEditLog(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            $id_teacher = $arrayinfotoken->id_teacher_student;
            $log = User::getListData(User::$ConnectDBWebsite,'log_group', ['id_teacher_edit'], 'id',$request->input('id_log_group'))[0];
            if ($log->id_teacher_edit==null || $log->id_teacher_edit==$id_teacher)
            {
                User::UpdateColumn("log_group",['id','=',$request->input('id_log_group')], ["id_teacher_edit"=>$id_teacher]); 
                return response()->json(["permit_edit"=>true,"info" => "Возможность редактирования журнала доступна."]);
            }
            else return response()->json(["permit_edit"=>false,"error" => "На данный момент журнал редактируется. Ожидайте."]);
        }
    }

    // журнал успеваемости студента по конкретному предмету
    public function ListGradesDisciplineStudent(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            $log = json_decode(Log::getGradebookStudent($arrayinfotoken->id_teacher_student, $request->input('id_disc_flow'))[0]->log_group_json);

            $ListGradesDiscipline=[];
            for($i=0; $i<count($log->tasks);$i++)
            {
                $ListGradesDiscipline[$i] = User::SearchRecordbyId("education_material", ['topic_material','min_score','max_score'], 'id', $log->tasks[$i]->id_educat_material);
                for ($j=0; $j<$log->tasks[$i]->array_students;$j++)
                {
                    if ($log->tasks[$i]->array_students[$j]->id_student == $arrayinfotoken->id_teacher_student)
                    {
                        $datastudent = $log->tasks[$i]->array_students[$j];
                        if($datastudent->date==null) $date="отсутствует";
                        else $date=$datastudent->date;

                        if($datastudent->score==null) $score="отсутствует";
                        else $score=$datastudent->score;

                        if($datastudent->id_teacher==null) $teacher="отсутствует";
                        else $teacher=User::getListData(User::$ConnectDBWebsite, "teacher", ['user.surname','user.name','user.patronymic'], 
                        'teacher.id', $datastudent->id_teacher,'user', 'user.id','teacher.id_user')[0];

                        
                        $ListGradesDiscipline[$i]->type_execution =$datastudent->type_execution;
                        $ListGradesDiscipline[$i]->score=$score;
                        $ListGradesDiscipline[$i]->date=$date;
                        $ListGradesDiscipline[$i]->teacher=$teacher;
                        break;
                    }
                }   
            }
            return response()->json($ListGradesDiscipline);
        }
    }

    // отметиться на паре
    public function ResultClickAttendanceButton(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));

            // массив групп студента
            $groups = User::SeachRecordsbyWhere("group_student", "id_student=?", $arrayinfotoken->id_teacher_student,"id_group");

            $info_button_post = User::SearchRecordbyId("posts", ['id_log_disc_flow','date_class','id_type_class'], 'id', $request->input('id_post'));
            $type_class = User::SearchRecordbyId("type_class", ['short_name'], 'id',  $info_button_post->id_type_class)->short_name;

            
            for ($i=0;$i<count($groups);$i++)
            {
                $log_group = User::SeachRecordsbyWhere("log_group", "id_log=? and id_group=?",  [$info_button_post->id_log_disc_flow,$groups[$i]->id_group],
                "id, log_group_json");
                if ($log_group!=null)
                {
                    $log = json_decode($log_group[0]->log_group_json);
                    for ($j=0; $j<count($log->attendance_group); $j++)
                    {
                        if ($log->attendance_group[$j]->date==$info_button_post->date_class && strcmp($log->attendance_group[$j]->type_class, $type_class)==0)
                        {
                            for ($k=0; $k<count($log->attendance_group[$j]->array_students); $k++)
                            {
                                if ($log->attendance_group[$j]->array_students[$k]->id_student==$arrayinfotoken->id_teacher_student)
                                {
                                    $log->attendance_group[$j]->array_students[$k]->presence_class="+";
                                    User::UpdateColumn("log_group",['id','=',$log_group[0]->id], ["log_group_json"=>json_encode($log)]); 
                                    return response()->json(["info"=>"Вы благополучно отметились на занятии."]);
                                }
                            }
                        break;
                        }
                    }
                break;
                }
            }
        }
    }
}
