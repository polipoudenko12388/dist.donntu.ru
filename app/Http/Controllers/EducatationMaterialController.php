<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
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
            // проверка темы уже на сущ. (не может быть пустая, если доб. ЛБ)
            $Seachtopicalmaterial =  User::SeachRecordsbyWhere("education_material", "topic_material=? and id_disc_flow=? and id_type_material=1", [$request->input('topic_material'),
            $request->input('id_disc_flow')]);
            if(count($Seachtopicalmaterial)>0)  return response()->json(["error"=>"Учебный материал с данной темой уже существует."]);
            else if ((trim($request->input('topic_material'))==null) && $request->input('id_type_material')==1) return response()->json(["error"=>"Выполните ввод темы добавляемого материала."]);
            else
            {
                // проверка что min_score < max_score
                if ((int)$request->input('min_score')>=(int)$request->input('max_score')) return response()->json(["error"=>"Не правильный ввод отметок."]);
                else
                {
                    $folder = User::SearchRecordbyId("discipline_flow",['folder'], 'id',$request->input('id_disc_flow'));
                    $datateacher = User::SearchRecordbyId("user", ['surname','name','patronymic'], 'id', $arrayinfotoken->id_user);
                    $date = date("Y-m-d", strtotime($request->input('date_assignment')));
                    $text = "Пользователь ".$datateacher->surname." ".$datateacher->name." ".$datateacher->patronymic." добавил(-а)";
                    
                    if ($request->input('id_type_material')==2) // Лекция
                    {
                        $pathdir = $folder->folder."/lectures";   // создание папки с файлами lectures 
                        $text=$text." новую лекцию.";
                        $lectures = User::SeachRecordsbyWhere('education_material', "id_disc_flow=? and id_type_material=2",[$request->input('id_disc_flow')]);
                        if (count($lectures)==0)
                        {
                            // добавление задания в education_material
                            $id_newmaterial = User::getIdInsertRecord(array("id_disc_flow"=>$request->input('id_disc_flow'), "topic_material"=>trim($request->input('topic_material')),
                            "id_type_material"=>$request->input('id_type_material'), "id_teacher_added"=>$arrayinfotoken->id_teacher_student,
                            "date_added"=>date("Y-m-d"),"folder"=>$pathdir),"education_material");

                            Storage::disk('mypublicdisk')->makeDirectory($pathdir);
                        }
                    }
                    else  if ($request->input('id_type_material')==1) // ЛБ
                    {
                        $id_newmaterial = User::getIdInsertRecord(array("id_disc_flow"=>$request->input('id_disc_flow'), "topic_material"=>trim($request->input('topic_material')),
                        "id_type_material"=>$request->input('id_type_material'), "id_type_assessment"=>$request->input('id_type_assessment'),
                        "id_teacher_added"=>$arrayinfotoken->id_teacher_student,"date_added"=>date("Y-m-d"), "date_assignment"=>$date,
                        "max_score"=>$request->input('max_score'),  "min_score"=>$request->input('min_score')),"education_material");
                        // создание папки с файлами tasks 
                        $pathdir = $folder->folder."/tasks/id".$id_newmaterial."task/filesteacher";  
                        $text=$text." новое задание по теме: \"".trim($request->input('topic_material'))."\".";
                        User::UpdateColumn("education_material",['id','=',$id_newmaterial], ["folder"=>$pathdir]); // добавление папки

                        // добавление столбца в журнал успеваемости
                        $list_log_groups_flow = User::getListData(User::$ConnectDBWebsite, "log_disc_flow", ['log_disc_flow.id_type_log','log_group.id as id_log_group','log_group.id_group'], 'id_disc_flow', 
                        $request->input('id_disc_flow'), "log_group", "log_disc_flow.id","log_group.id_log");
                        for ($i=0;$i<count($list_log_groups_flow);$i++)
                        {
                            if ($list_log_groups_flow[$i]->id_type_log==2)
                            {
                                $foldertaskgroup=dirname($pathdir,1)."/id".$list_log_groups_flow[$i]->id_group."group"; // папка группы 
                            
                                User::UpdateColumnJson("log_group", "id=?", [$list_log_groups_flow[$i]->id_log_group], 
                                "log_group_json","JSON_ARRAY_APPEND(`log_group_json`, '$.tasks', 
                                json_object('id_educat_material',". $id_newmaterial.",'topic_material','".trim($request->input('topic_material'))."','folder','".$foldertaskgroup."','array_students', JSON_ARRAY()))");

                                Storage::disk('mypublicdisk')->makeDirectory($foldertaskgroup);
                                $count = User::SeachRecordsbyWhere("log_group", "id=?", [$list_log_groups_flow[$i]->id_log_group],
                                "JSON_LENGTH(log_group_json,'$.tasks') as count")[0]->count;

                                $listStudents = Group::getListStudentsIdGroup($list_log_groups_flow[$i]->id_group);
                                $type_execution = User::SearchRecordbyId("type_execution", ['name'], 'id', 1)->name;
                                for ($j=0; $j<count($listStudents); $j++)
                                {
                                    // создание папки
                                    $folder_files_student = $foldertaskgroup."/id".$listStudents[$j]->id_student."".File::translit($listStudents[$j]->surname); // папка студента группы
                        
                                    User::UpdateColumnJson("log_group", "id=?", [$list_log_groups_flow[$i]->id_log_group], 
                                    "log_group_json","JSON_ARRAY_APPEND(`log_group_json`, '$.tasks[".($count-1)."].array_students',
                                    json_object('id_student',".$listStudents[$j]->id_student.",'name','".$listStudents[$j]->name."','surname','".$listStudents[$j]->surname."', 
                                    'patronymic','".$listStudents[$j]->patronymic."','id_type_execution',1,'type_execution','".$type_execution."','score',null,'date',null,
                                    'id_teacher',null,'folder','".$folder_files_student."'))");

                                    Storage::disk('mypublicdisk')->makeDirectory($folder_files_student);
                                }
                                
                            }
                        }
                    }
                    
                    // добавление файлов в папку (если они присутствуют)
                    GeneralUserController::UploadFiletoServer($request,$pathdir,'files'); 
                    
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
            $education_material = User::SearchRecordbyId("education_material",['folder','id_type_material'], 'id',$request->input('id_educat_material'));
            if ($education_material->id_type_material==1) Storage::disk('mypublicdisk')->deleteDirectory(dirname($education_material->folder,1));
            else  if ($education_material->id_type_material==2) Storage::disk('mypublicdisk')->deleteDirectory($education_material->folder);
        
            // удаление задания
            User::DeleteRecord("education_material","id=?",[$request->input('id_educat_material')]);
            return response()->json(["info"=>"Удаление задания прошло успешно."]); 


            // удаление задания из журналов групп потока
            $list_log_groups_flow = User::getListData(User::$ConnectDBWebsite, "log_disc_flow", ['log_disc_flow.id_type_log','log_group.id as id_log_group'], 'id_disc_flow', 
            $request->input('id_disc_flow'), "log_group", "log_disc_flow.id","log_group.id_log");
            for ($i=0;$i<count($list_log_groups_flow);$i++)
            {
                if ($list_log_groups_flow[$i]->id_type_log==2)
                {
                    $count = User::SeachRecordsbyWhere("log_group", "id=?", [$list_log_groups_flow[$i]->id_log_group],
                    "JSON_LENGTH(log_group_json,'$.tasks') as count")[0]->count;
                    for ($j=0;$j<$count;$j++)
                    {
                        User::UpdateColumnJson("log_group", "id=? and log_group_json->'$.tasks[".$j."].id_educat_material'=?", 
                        [$list_log_groups_flow[$i]->id_log_group,(int)$request->input('id_educat_material')], 
                        "log_group_json","JSON_REMOVE(`log_group_json`, '$.tasks[".$j."]')");
                    }
                }
            }

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
                   
                    // редактирование журнала 
                    $list_log_groups_flow = User::getListData(User::$ConnectDBWebsite, "log_disc_flow", ['log_disc_flow.id_type_log','log_group.id as id_log_group','log_group.log_group_json'], 'id_disc_flow', 
                    $request->input('id_disc_flow'), "log_group", "log_disc_flow.id","log_group.id_log");
                    for ($i=0;$i<count($list_log_groups_flow);$i++)
                    {
                        if ($list_log_groups_flow[$i]->id_type_log==2)
                        {
                            $log_group = json_decode($list_log_groups_flow[$i]->log_group_json);
                            for ($j=0;$j<count($log_group->tasks);$j++)
                            {
                                if ($log_group->tasks[$j]->id_educat_material == $request->input('id_educat_material'))
                                {
                                    $log_group->tasks[$j]->topic_material=trim($request->input('topic_material'));
                                    User::UpdateColumn("log_group", ['id','=',$list_log_groups_flow[$i]->id_log_group],  ["log_group_json"=>json_encode($log_group)]);
                                    break;
                                }
                            }
                        }
                    }

                   return response()->json(["info"=>"Редактирование задания прошло успешно."]); 
               }
            }
        }
    }

    public function ListMaterialTasks(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            $strwheresql="id_disc_flow=? and id_type_material=1";
            if ($request->input('id_educat_material')!=null) $strwheresql=$strwheresql." and id=?";
            $buf = User::SeachRecordsbyWhere("education_material", $strwheresql,[$request->input('id_disc_flow'),$request->input('id_educat_material')]);

            $ListTasks=[];
            for ($i=0; $i<count($buf);$i++)
            {
                $type_assessment = User::SearchRecordbyId("type_assessment", 'name', 'id', $buf[$i]->id_type_assessment);
                $datateacher = User::getListData(User::$ConnectDBWebsite, "teacher", ["user.surname","user.name","user.patronymic"], 
                'teacher.id', $buf[$i]->id_teacher_added,"user","teacher.id_user","user.id")[0];
                $ListTasks[$i] = array("id_educat_material"=>$buf[$i]->id, "id_disc_flow"=>$buf[$i]->id_disc_flow, "topic_material"=>$buf[$i]->topic_material,
                "id_type_assessment"=>$buf[$i]->id_type_assessment, "type_assessment"=>$type_assessment->name,"id_teacher_added"=>$buf[$i]->id_teacher_added, 
                "surname"=>$datateacher->surname, "name"=>$datateacher->name,"patronymic"=>$datateacher->patronymic, "date_added"=>$buf[$i]->date_added, 
                "date_assignment"=>$buf[$i]->date_assignment, "explanation_task"=>$buf[$i]->explanation_task, "max_score"=>$buf[$i]->max_score, "min_score"=>$buf[$i]->min_score);

                $files=Storage::disk('mypublicdisk')->files($buf[$i]->folder);
                $ListTasks[$i]['files']=[];
                for ($j=0;$j<count($files);$j++)
                {
                    $ListTasks[$i]['files'][$j]=Storage::disk('mypublicdisk')->url($files[$j]);
                }
            }
            
            return response()->json($ListTasks);
        }
    }

     //список типов материала (Лекция или ЛБ)
     public function ListTypeMaterial(Request $request)
     {
         // проверка токена
         $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
         if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
         else  { return response()->json(User::getListData(User::$ConnectDBWebsite, "type_material", ['id as id_type','name as name_type'])); }   
     }

    //список типов оценивания (оценка или баллы)
    public function ListTypeAssessment(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  { return response()->json(User::getListData(User::$ConnectDBWebsite, "type_assessment", ['id as id_type','name as name_type'])); }   
    }

    // тип результатов оценивания (назначено, сдано, с оценкой, доработать)
    public function ListTypeExecution(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  { return response()->json(User::getListData(User::$ConnectDBWebsite, "type_execution", ['id as id_type','name as name_type'])); }   
    }

    // файлы лекций или лабораторных дисциплины потока
    public function FilesEducatMaterial(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            $listmaterial = User::SeachRecordsbyWhere("education_material", 'id_disc_flow=? and id_type_material=?', [$request->input('id_disc_flow'), $request->input('id_type_material')],'id as id_educ_material,id_type_material,folder');

            $files=[]; $l=0;
            for ($i=0; $i<count($listmaterial); $i++)
            {
                $buf=Storage::disk('mypublicdisk')->files($listmaterial[$i]->folder);
                for ($j=0;$j<count($buf);$j++)
                {
                    $files[$l]['id_educ_material']=$listmaterial[$i]->id_educ_material;
                    $files[$l]['name_file']=pathinfo($buf[$j])['filename'].".".pathinfo($buf[$j])['extension'];
                    $files[$l]['file']=Storage::disk('mypublicdisk')->url($buf[$j]);
                    $l++;
                }
            }
            return response()->json($files);       
        }   
    }

    public function DeleteFileLecture(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            $lecture = User::SearchRecordbyId("education_material", ["folder"], 'id', $request->input('id_educ_material'));
            Storage::disk('mypublicdisk')->delete($lecture->folder."/".$request->input('name_file'));
            return response()->json(["info"=>"Удаление лекционного материала прошло успешно."]);
        }
    }
}
