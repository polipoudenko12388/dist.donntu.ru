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

    public function getLog(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            $id_disc_flow = User::SeachRecordsbyWhere("discipline_flow", "id_list_discipline=? and id_flow=?", [$request->input('id_disc'), $request->input('id_flow')],"id")[0]->id;
            $id_log_disc_flow = User::SeachRecordsbyWhere("log_disc_flow", "id_disc_flow=? and id_type_log=?", [$id_disc_flow, $request->input('id_type')],"id")[0]->id;
            if ( $id_log_disc_flow!=null)
            {
                $log_group = User::SeachRecordsbyWhere("log_group", "id_log=? and id_group=?", [$id_log_disc_flow, $request->input('id_group')],"id,log_group_json")[0];
                return response()->json(["id_log_group"=>$log_group->id, "log"=>json_decode($log_group->log_group_json)]);
            }    
        } 

    }
}
