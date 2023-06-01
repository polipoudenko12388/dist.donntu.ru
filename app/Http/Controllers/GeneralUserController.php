<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\HumanWorkes;
use App\Models\HumanStudent;

class GeneralUserController extends Controller
{
    // проверка токена
    public static function VerifactionToken($id_user_reg, $token)
    {
        return User::SeachRecordsbyWhere("tokens_authorization", "id_registration=? and token=?", [$id_user_reg,$token]);
    }

    // данные о пользователях - профиль
    public function DataProfile(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else
        {
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));

            if ($request->input('id_student_teacher')!=null && $request->input('role')!=null)
            {
                $id_student_teacher=$request->input('id_student_teacher'); $role=$request->input('role');
                if ($role==1) $id_user = User::SearchRecordbyId("student", ["id_user"], "id", $id_student_teacher)->id_user;
                else if ($role==5) $id_user = User::SearchRecordbyId("teacher",["id_user"], "id", $id_student_teacher)->id_user;
            } 
            else { $id_user=$arrayinfotoken->id_user; $role=$arrayinfotoken->id_role_user; }
            
            $id_human=User::SearchRecordbyId("user","id_human_db_univ", "id", $id_user);
            $date_registration = User::SearchRecordbyId("registration","date_registration", "id", $id_user);

            if ($role==5) // преподаватель
            {
                $HumanWorkes = new HumanWorkes();
                $id_role_workes=User::SearchRecordbyId("role_user","id_role_db_univ", "id", $role);
                $dataWorkes = array_merge((array)$HumanWorkes->getDataWorkes($id_human->id_human_db_univ, $id_role_workes->id_role_db_univ),(array)$date_registration);
                if ($dataWorkes['photo']!=null) $dataWorkes['photo']=Storage::disk('mypublicdisk')->url($dataWorkes['photo']);
                else $dataWorkes['photo']=Storage::disk('mypublicdisk')->url('defaultimage/user_photo.svg');
                return response()->json($dataWorkes);
            }
            else if ($role==1) // студент
            {
                $HumanStudent = new HumanStudent();
                $dataStudent = array_merge((array)$HumanStudent->getDataStudents($id_human->id_human_db_univ),(array)$date_registration);
                if ($dataStudent['photo']!=null) $dataStudent['photo']=Storage::disk('mypublicdisk')->url($dataStudent['photo']);
                else $dataStudent['photo']=Storage::disk('mypublicdisk')->url('defaultimage/user_photo.svg');
                return response()->json($dataStudent);
            } 
        }
    }

    public function exit(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else
        {
            User::UpdateColumn("tokens_authorization", ['id','=',$token_verification[0]->id], ["data_save_note"=>date('Y-m-d'),'token'=>null]);
            return ["info" => "Вы вышли из системы."];
        }
    }

    // загрузка файлов на сервер
    public static function UploadFiletoServer(Request $request, $path, $key)
    {
        // foreach ($request->files as $key=>$file) 
        if ($request->hasFile($key)) 
        {
            $file=$request->file($key);
            for ($i=0; $i<count($file); $i++)
            {
                // получить имя файла без расширения
                $filename =  pathinfo($file[$i]->getClientOriginalName(),PATHINFO_FILENAME);
                $extension = pathinfo($file[$i]->getClientOriginalName(), PATHINFO_EXTENSION);
                // перевести имя в латиницу + доб. тип 
                $filename = File::translit($filename);
                // сохранить в папке конкретного поста 
                $file[$i]->storeAs($path,$filename.".".$extension,'mypublicdisk');
            } 
        }
    }

    // список групп со студентами потока дисциплины с выводом данных
    public function ListStudentsDiscipline(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else
        {
            // получить список групп потока дисциплины
            $listgroups = User:: getListData(User::$ConnectDBWebsite, "discipline_flow", ['discipline_flow.id_flow', 'flow_group.id_group'], 
            'discipline_flow.id', $request->input('id_disc_flow'),"flow_group", "discipline_flow.id_flow","flow_group.id_flow");
            // получить имя потока и групп 
            $groupsstudents=["name_flow"=>User::SearchRecordbyId("flow", ['name'], 'id', $listgroups[0]->id_flow)->name, "groups"=>null];
            for ($i=0; $i<count($listgroups);$i++)
            {
                $namegroup = User::SearchRecordbyId("group", ['name'], 'id', $listgroups[$i]->id_group);
                $groupsstudents['groups'][$i]['namegroup']=$namegroup->name;
                $students = User:: getListData(User::$ConnectDBWebsite, "group_student", ['group_student.id_student','student.id_user'], 
                'group_student.id_group', $listgroups[$i]->id_group,"student", "student.id","group_student.id_student");
                
                $groupsstudents['groups'][$i]['count_students']=count($students);
                $groupsstudents['groups'][$i]['students']=null;
                for ($j=0; $j<count($students); $j++)
                {
                    $datastudent=User::SearchRecordbyId("user", ['surname','name','patronymic'], 'id', $students[$j]->id_user);
                    
                    $groupsstudents['groups'][$i]['students'][$j]=array('id_student'=>$students[$j]->id_student,"role"=>1,'surname'=>$datastudent->surname,
                    'name'=>$datastudent->name, 'patronymic'=>$datastudent->patronymic,"photo"=>Storage::disk('mypublicdisk')->url('defaultimage/user_photo.svg'));  
                }
            }

            return response()->json($groupsstudents);
        }
    }


}
