<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class EducatationMaterialController extends Controller
{
    
    public function addrecordinpost($arrayinfotoken,$request,$oldtopicalmaterial,$pathtext)
    {
        $datateacher=User::SearchRecordbyId("user", ['surname','name','patronymic'], 'id', $arrayinfotoken->id_user);
        $text="Пользователь ".$datateacher->surname." ".$datateacher->name." ".$datateacher->patronymic." ".$pathtext."\"".$oldtopicalmaterial."\" ";
        // добавление в т. posts инфы о том, что было удалено/отредактировано задание/лекция
        User::InsertRecord(["id_disc_flow"=>$request->input('id_disc_flow'),"id_teacher_creator"=>$arrayinfotoken->id_teacher_student,
        "date_create_post"=>date('Y-m-d H:i:s'),"id_type_post"=>2,"text"=>$text, "attendance_button"=>(int)false], "posts");
    }
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
            if($request->input('id_type_material')==1 && count($Seachtopicalmaterial)>0)  return response()->json(["error"=>"Учебный материал с данной темой уже существует."]);
            else if ((trim($request->input('topic_material'))==null) && $request->input('id_type_material')==1) return response()->json(["error"=>"Выполните ввод темы добавляемого материала."]);
            else
            {
                // проверка что min_score < max_score
                if ((int)$request->input('min_score')>=(int)$request->input('max_score') && $request->input('id_type_material')==1) return response()->json(["error"=>"Не правильный ввод отметок."]);
                else
                {
                    $folder = User::SearchRecordbyId("discipline_flow",['folder'], 'id',$request->input('id_disc_flow'));
                    $datateacher = User::SearchRecordbyId("user", ['surname','name','patronymic'], 'id', $arrayinfotoken->id_user);
                    if ($request->input('date_assignment'!=null)) $date = date("Y-m-d", strtotime($request->input('date_assignment')));
                    else $date=null;
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
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            $oldtopicalmaterial = User::SearchRecordbyId("education_material", ['topic_material'], 'id', $request->input('id_educat_material'))->topic_material;

            // удаление папки
            $education_material = User::SearchRecordbyId("education_material",['folder','id_type_material'], 'id',$request->input('id_educat_material'));
            if ($education_material->id_type_material==1) Storage::disk('mypublicdisk')->deleteDirectory(dirname($education_material->folder,1));
            else  if ($education_material->id_type_material==2) Storage::disk('mypublicdisk')->deleteDirectory($education_material->folder);
        
            // удаление задания
            User::DeleteRecord("education_material","id=?",[$request->input('id_educat_material')]);
            
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

            $this->addrecordinpost($arrayinfotoken,$request,$oldtopicalmaterial,"удалил(-а) задание по теме ");

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
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            $oldtopicalmaterial = User::SearchRecordbyId("education_material", ['topic_material'], 'id', $request->input('id_educat_material'))->topic_material;
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

                    $this->addrecordinpost($arrayinfotoken,$request,$oldtopicalmaterial," отредактировал(-а) задание по теме ");
                    
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

    // тип результатов оценивания (назначено, сдано, с оценкой, доработатьб сдано с опозданием)
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
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));

            $lecture = User::SearchRecordbyId("education_material", ["folder"], 'id', $request->input('id_educat_material'));
            Storage::disk('mypublicdisk')->delete($lecture->folder."/".$request->input('name_file'));

            $this->addrecordinpost($arrayinfotoken,$request,$request->input('name_file'),"удалил(-а) лекционный материал ");
            return response()->json(["info"=>"Удаление лекционного материала прошло успешно."]);
        }
    }

    // получение окна конкретного задания потока дисциплины
    public function ResultGetTaskDiscipline(Request $request)
    { 
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            // по id_disc_flow получить журналы групп данного потока дисциплины
            $list_log_groups_flow = User::getListData(User::$ConnectDBWebsite, "log_disc_flow", ['log_disc_flow.id_type_log','log_group.id as id_log_group','log_group.log_group_json'], 
            'id_disc_flow', $request->input('id_disc_flow'), "log_group", "log_disc_flow.id","log_group.id_log");
            
            $count=0; 
            for ($i=0;$i<count($list_log_groups_flow);$i++)
            {
                $count_type_naznacheno=0; $count_type_sdano=0; $count_in_score=0;$count_type_dorabotat=0; $count_type_sdano_opozd=0;
                if ($list_log_groups_flow[$i]->id_type_log==2) // журнал успеваемости
                {
                    $log = json_decode($list_log_groups_flow[$i]->log_group_json);
                    $ListGroups[$count]['group']["name_group"]=$log->name_group;
                    for ($j=0;$j<count($log->tasks);$j++) // цикл по заданиям текущего журнала
                    {  
                        if ($log->tasks[$j]->id_educat_material==$request->input('id_educat_material')) // необх. данные по нужному. заданию
                        {
                            $ListGroups[$count]['group']["count_students"]=count($log->tasks[$j]->array_students);
                            $ListGroups[$count]['group']["id_log_group"] = $list_log_groups_flow[$i]->id_log_group;
                            $ListGroups[$count]['group']['array_students']=[];
                            for ($k=0;$k<count($log->tasks[$j]->array_students);$k++)
                            {
                                if ($log->tasks[$j]->array_students[$k]->score==null) $score="неизвестно";
                                else $score=$log->tasks[$j]->array_students[$k]->score;

                                if ($log->tasks[$j]->array_students[$k]->id_type_execution==1) $count_type_naznacheno++;
                                else if ($log->tasks[$j]->array_students[$k]->id_type_execution==2) $count_type_sdano++;
                                else if ($log->tasks[$j]->array_students[$k]->id_type_execution==3) $count_in_score++;
                                else if ($log->tasks[$j]->array_students[$k]->id_type_execution==4) $count_type_dorabotat++;
                                else $count_type_sdano_opozd++;

                                $ListGroups[$count]['group']['array_students'][$k] = array("id_student"=>$log->tasks[$j]->array_students[$k]->id_student,
                                "surname"=>$log->tasks[$j]->array_students[$k]->surname,
                                "name"=>$log->tasks[$j]->array_students[$k]->name,"patronymic"=>$log->tasks[$j]->array_students[$k]->patronymic,
                                "type_execution"=>$log->tasks[$j]->array_students[$k]->type_execution,"score"=>$score);
                            }
                        }
                        else break;
                    }  
                    $ListGroups[$count]['countrecord']=array("count_type_naznacheno"=>$count_type_naznacheno,"count_type_sdano"=>$count_type_sdano,
                    "count_in_score"=>$count_in_score,"count_type_dorabotat"=>$count_type_dorabotat,"count_type_sdano_opozd"=>$count_type_sdano_opozd);
                    $count++;
                }
            }

            return response()->json($ListGroups);
        }
    }

    // получение окна задания конкретного студента потока дисциплины
    public function ResultGetTaskStudentDiscipline(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            $log = json_decode(User::SearchRecordbyId('log_group', ['log_group_json'], 'id', $request->input('id_log_group'))->log_group_json);
            $taskstudent=[];
            for ($i=0; $i<count($log->tasks);$i++)
            {
                if ($log->tasks[$i]->id_educat_material==$request->input('id_educat_material'))
                {
                    for ($j=0; $j<count($log->tasks[$i]->array_students); $j++)
                    {
                        if ($log->tasks[$i]->array_students[$j]->id_student==$request->input('id_student'))
                        {
                            $bufstudent=$log->tasks[$i]->array_students[$j];

                            if ($bufstudent->score==null) $score="неизвестно";
                            else $score=$bufstudent->score;

                            if ($bufstudent->date==null) $date="отсутствует";
                            else $date=$bufstudent->date;

                            $taskstudent=array("group"=>$log->name_group,"surname"=>$bufstudent->surname, "name"=>$bufstudent->name, "patronymic"=>$bufstudent->patronymic,
                            "id_type_execution"=>$bufstudent->id_type_execution, "type_execution"=>$bufstudent->type_execution, "date"=>$date, "score"=>$score);

                            $files=Storage::disk('mypublicdisk')->files($bufstudent->folder);
                            $taskstudent['files']=[];
                            for ($k=0;$k<count($files);$k++)
                            {
                                $taskstudent['files'][$k]=Storage::disk('mypublicdisk')->url($files[$k]);
                            }
                        break;
                        }
                    }
                    break;
                }
            }
            return response()->json($taskstudent);
        }

    }

    // сохранение в журнал результата проверки задания студента
    public function ResultSaveCheckTaskStudentDiscipline(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            $log = json_decode(User::SearchRecordbyId('log_group', ['log_group_json'], 'id', $request->input('id_log_group'))->log_group_json);
            for ($i=0; $i<count($log->tasks);$i++)
            {
                if ($log->tasks[$i]->id_educat_material==$request->input('id_educat_material'))
                {
                    for ($j=0; $j<count($log->tasks[$i]->array_students); $j++)
                    {
                        if ($log->tasks[$i]->array_students[$j]->id_student==$request->input('id_student'))
                        {
                            $log->tasks[$i]->array_students[$j]->score=$request->input('score');
                            $log->tasks[$i]->array_students[$j]->id_type_execution=$request->input('id_type_execution');
                            $log->tasks[$i]->array_students[$j]->type_execution=$request->input('type_execution');
                            $log->tasks[$i]->array_students[$j]->id_teacher=$arrayinfotoken->id_teacher_student;
                            break;
                        }   
                    }
                break;
                }
            }
        User::UpdateColumn("log_group",['id','=',$request->input('id_log_group')], ["log_group_json"=> json_encode($log)]); 
        return response()->json(["info"=>"Сохранение данных в журнал прошло успешно."]);
        }
    }

    // редактирование пояснения к заданию
    public function ResultEditExplanationTask(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            if (trim($request->input('explanation_task'))!=null)
            {
                User::UpdateColumn("education_material",['id','=',$request->input('id_educat_material')], ["explanation_task"=> trim($request->input('explanation_task'))]); 
                return response()->json(["info" => "Пояснение к заданию успешно отредактировано."]);
            }
            else return response()->json(["error" => "Выполните ввод пояснения к заданию."]);
        }
    }
}

