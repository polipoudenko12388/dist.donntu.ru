<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
}
