<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Group;
use App\Models\HumanWorkes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GeneralTeacherController extends Controller
{
    // данные о преподавателе - профиль
    public function DataProfile(Request $request)
    {
        $HumanWorkes = new HumanWorkes();

        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else
        {
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));

            $id_human=User::SearchRecordbyId("user","id_human_db_univ", "id", $arrayinfotoken->id_user);
            $id_role_workes=User::SearchRecordbyId("role_user","id_role_db_univ", "id", $arrayinfotoken->id_role_user);
            $date_registration = User::SearchRecordbyId("registration","date_registration", "id", $request->input('id_user_reg'));
            $dataWorkes = array_merge((array)$HumanWorkes->getDataWorkes($id_human->id_human_db_univ, $id_role_workes->id_role_db_univ),(array)$date_registration);
            if ($dataWorkes['photo']!=null) $dataWorkes['photo']=Storage::disk('mypublicdisk')->url($dataWorkes['photo']);
            else $dataWorkes['photo']=Storage::disk('mypublicdisk')->url('defaultimage/user_photo.svg');
            return response()->json($dataWorkes);
        }
    }

    // -------------------------------------------------
    // ЧЕРНОВИК ЧЕРНОВИК ЧЕРНОВИК ЧЕРНОВИК ЧЕРНОВИК
    public function getfile(Request $request)
    {
        // $file_path = public_path('storage/fileserver/photouser/0563d680-c690-4fd5-a222-eb31bd51554c.jpg');

        $file_path = public_path('storage/fileserver/photouser/default_fon_discipline.png');

        // return response()->download($file_path);
        
        // $disk = Storage::disk('mypublicdisk');
        // $file= $disk->url('/photouser/0563d680-c690-4fd5-a222-eb31bd51554c.jpg');
        
        // $file= $disk->path('photouser/default_fon_discipline.png');
        
        // $disk = Storage::disk('public');
        // $file= $disk->put('fileserver/photouser/pfoto1.jpg', 'j');
        // return response()->json($file);
        // return response()->download( $file);
    }
    //--------------------------------------------------


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
