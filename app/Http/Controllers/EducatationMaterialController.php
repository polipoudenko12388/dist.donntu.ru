<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class EducatationMaterialController extends Controller
{
    
    public function ResultAddMaterial(Request $request)
    {
         // проверка токена
         $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
         if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
         else  
         { 
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            // проверка темы уже на сущ. (не может быть пустая, особенно если доб. ЛБ)
            $Seachtopicalmaterial =  User::SeachRecordsbyWhere("education_material", "topic_material=? and id_disc_flow=?", [$request->input('topic_material'),
            $request->input('id_disc_flow')]);
            if(count($Seachtopicalmaterial)>0)  return response()->json(["error"=>"Учебный материал с данной темой уже существует."]);
            else if (trim($request->input('topic_material'))==null) return response()->json(["error"=>"Тема материала не была добавлена."]);
            else
            {
                // проверка что min_score < max_score
                if ((int)$request->input('min_score')>=(int)$request->input('max_score')) return response()->json(["error"=>"Не правильный ввод отметок."]);
                else
                {
                    $date = date("Y-m-d", strtotime($request->input('date_assignment')));
                    // добавление задания в education_material
                    $id_newmaterial = User::getIdInsertRecord(array("id_disc_flow"=>$request->input('id_disc_flow'), "topic_material"=>trim($request->input('topic_material')),
                    "id_type_material"=>$request->input('id_type_material'), "id_type_assessment"=>$request->input('id_type_assessment'),
                    "date_assignment"=>$date,"max_score"=>$request->input('max_score'), "min_score"=>$request->input('min_score')),"education_material");

                    $folder = User::SearchRecordbyId("discipline_flow",['folder'], 'id',$request->input('id_disc_flow'));
                    $datateacher = User::SearchRecordbyId("user", ['surname','name','patronymic'], 'id', $arrayinfotoken->id_user);
                    $text = "Пользователь ".$datateacher->surname." ".$datateacher->name." ".$datateacher->patronymic." добавил(-а)";
                    if ($request->input('id_type_material')==1) // ЛБ
                    {
                        // создание папки с файлами tasks 
                        $pathdir = $folder->folder."/tasks/id".$id_newmaterial."task/filesteacher";  
                        $text=$text." новое задание по теме: \"".trim($request->input('topic_material'))."\".";
                    }
                    else if ($request->input('id_type_material')==2) // Лекция
                    {
                        // создание папки с файлами lectures 
                        $pathdir = $folder->folder."/lectures";  
                        $text=$text." новую лекцию.";
                    }

                    Storage::disk('mypublicdisk')->makeDirectory($pathdir);
                    // добавление файлов в папку (если они присутствуют)
                    GeneralUserController::UploadFiletoServer($request,$pathdir,'files'); 
                    // добавление папки
                    User::UpdateColumn("education_material",['id','=',$id_newmaterial], ["folder"=>$pathdir]);

                    // добавление в т. posts инфы о том, что были выложены задания
                    
                    User::InsertRecord(["id_disc_flow"=>$request->input('id_disc_flow'),"id_teacher_creator"=>$arrayinfotoken->id_teacher_student,
                    "date_create_post"=>date('Y-m-d H:i:s'),"id_type_post"=>2,"text"=>$text, "attendance_button"=>(int)false, "date_end_button"=>null,
                    "id_type_class"=>null, "date_class"=>null], "posts");

                    return response()->json(["info"=>"Добавление учебного материала прошло успешно."]);
                }
            }
            

         }
    }

    public function ResultDeleteMaterialTasks(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            // удаление папки
            $folder = User::SearchRecordbyId("education_material",['folder'], 'id',$request->input('id_educat_material'));
            Storage::disk('mypublicdisk')->deleteDirectory(dirname($folder->folder,1));
        
            // удаление задания
            User::DeleteRecord("education_material","id=?",[$request->input('id_educat_material')]);
            return response()->json(["info"=>"Удаление задания прошло успешно."]); 
        }
    }

    public function ResultEditMaterialTasks(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
           // проверка темы уже на сущ. (не может быть пустая)
           $Seachtopicalmaterial =  User::SeachRecordsbyWhere("education_material", "topic_material=? and id_disc_flow=?", [$request->input('topic_material'),
           $request->input('id_disc_flow')]);
           if(count($Seachtopicalmaterial)>0)  return response()->json(["error"=>"Учебный материал с данной темой уже существует."]);
           else if (trim($request->input('topic_material'))==null) return response()->json(["error"=>"Тема материала не была добавлена."]);
           else
           {
               // проверка что min_score < max_score
               if ((int)$request->input('min_score')>=(int)$request->input('max_score')) return response()->json(["error"=>"Не правильный ввод отметок."]);
               else
               {
                   $date = date("Y-m-d", strtotime($request->input('date_assignment')));

                   // удаление файлов в папке задания
                   $folder = User::SearchRecordbyId("education_material",['folder'], 'id',$request->input('id_educat_material'));
                   $files  = Storage::disk('mypublicdisk')->allFiles($folder->folder);
                   Storage::disk('mypublicdisk')->delete($files);
                   
                   // добавление файлов в папку (если они присутствуют)
                   GeneralUserController::UploadFiletoServer($request,$folder->folder,'files'); 

                   // обновление данных задания true=1; false=0
                   User::UpdateColumn("education_material", ['id','=',$request->input('id_educat_material')], 
                   ["topic_material"=>trim($request->input('topic_material')), "id_type_assessment"=>$request->input('id_type_assessment'),
                   "date_assignment"=>$date,"max_score"=>$request->input('max_score'), "min_score"=>$request->input('min_score')]);

                   return response()->json(["info"=>"Редактирование задания прошло успешно."]); 
               }
            }
        }
    }

     //список типов материала
     public function ListTypeMaterial(Request $request)
     {
         // проверка токена
         $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
         if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
         else  { return response()->json(User::getListData(User::$ConnectDBWebsite, "type_material", ['id as id_type','name as name_type'])); }   
     }

        //список типов оценивания
        public function ListTypeAssessment(Request $request)
        {
            // проверка токена
            $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
            if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
            else  { return response()->json(User::getListData(User::$ConnectDBWebsite, "type_assessment", ['id as id_type','name as name_type'])); }   
        }

        public function ListTypeExecution(Request $request)
        {
            // проверка токена
            $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
            if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
            else  { return response()->json(User::getListData(User::$ConnectDBWebsite, "type_execution", ['id as id_type','name as name_type'])); }   
        }
}
