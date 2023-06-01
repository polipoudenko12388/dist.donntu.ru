<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GeneralTeacherController extends Controller
{
    // ОБЩИЕ
    
    //список институтов
    public function ListInstitutions(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        { 
            return response()->json(User::getListData(User::$ConnectDBUniv, "institute", ['id as id_institute','name as name_institute'],
            'id',$request->input('id_institute'))); 
        }   
    }

    //список факультетов
    public function ListFaculties(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            return response()->json(Group::getListFacultyorDepartment('faculty', ['faculty.id as id_faculty', 'faculty.name as name_faculty'], 
            "faculty.id","info_inst_facul_depart.id_faculty",$request->input('id_institute'),$request->input('id_faculty'))); 
        }   
    }

    //список кафедр
    public function ListDepartments(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            return response()->json(Group::getListFacultyorDepartment('department', ['department.id as id_department', 'department.name as name_department'], 
            "department.id","info_inst_facul_depart.id_department",$request->input('id_institute'),$request->input('id_faculty'),$request->input('id_department'))); 
        }    
    }

    // список преподавателей
    public function ListTeachers(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            $ListidTeachers=User::getListData(User::$ConnectDBWebsite, "teacher", ['id','id_user']); 
            if (count($ListidTeachers)==0) $ListTeachers=null;
            else
            {
                for ($i=0; $i<count($ListidTeachers); $i++)
                {
                    $buf=User::SearchRecordbyId("user", ['surname','name','patronymic'], 'id', $ListidTeachers[$i]->id_user);
                    $ListTeachers[$i]=array("id_teacher"=>$ListidTeachers[$i]->id,"surname"=>$buf->surname, "name"=>$buf->name,"patronymic"=>$buf->patronymic); 
                }
            }
        return response()->json($ListTeachers); 
        }  
    }
}
