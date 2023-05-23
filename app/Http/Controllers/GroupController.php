<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{
    public function ListGroupsInfo(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            return response()->json(Group::getListGroupInfo($request->input('id_institute'),$request->input('id_faculty'),
            $request->input('id_department'),$request->input('namegroup'))); 
        } 
    }

    public function ListGroups(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            return response()->json(User::getListData(User::$ConnectDBWebsite,'group', ['group.id as id_group','name as name_group'],'id_flow',$request->input('id_flow'),
            "flow_group","flow_group.id_group","group.id")); 
        } 
    }

    public function ListStudentsGroup(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            $listStudents = Group::getListStudentsIdGroup($request->input('id_group'));
            if (count($listStudents)==0) return response()->json(["error" => "Зарегистрированных студентов данный группы в базе не обнаружено."]);
            else return response()->json($listStudents); 
        } 
    }
}
