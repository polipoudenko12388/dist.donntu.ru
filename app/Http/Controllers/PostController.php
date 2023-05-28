<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Group;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PostController extends Controller
{
    public function ResultCreatePost(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            
            // добавление поста в т. posts true=1; false=0
            $date = date("Y-m-d H:i:s", strtotime($request->input('date_end_button')));
            $id_post=User::getIdInsertRecord(["id_disc_flow"=>$request->input('id_disc_flow'),"id_teacher_creator"=>$arrayinfotoken->id_teacher_student,
            "date_create_post"=>date('Y-m-d H:i:s'),"id_type_post"=>1,"text"=>$request->input('text'), 
            "attendance_button"=>(int)(boolval($request->input('attendance_button'))), "date_end_button"=>$date,
            "id_type_class"=>$request->input('id_type_class'), "date_class"=>date('Y-m-d')], "posts");

            // создание папки поста
            $folder = User::SearchRecordbyId("discipline_flow",['folder'], 'id',$request->input('id_disc_flow'));
            $pathdir = $folder->folder."/posts/postid".$id_post;
            Storage::disk('mypublicdisk')->makeDirectory($pathdir);

            User::UpdateColumn("posts",['id','=',$id_post], ["folder"=>$pathdir]);

            GeneralUserController::UploadFiletoServer($request,$pathdir,'files');
            
            if ($request->input('attendance_button') == true)
            {
                
                // проверить, сущ. ли уже нужный столбец с указанными параметрами по id_disc_flow во всех группах потока
                $id_log_disc_flow = User::SeachRecordsbyWhere("log_disc_flow", "id_disc_flow=? and id_type_log=?", [$request->input('id_disc_flow'),1],"id")[0]->id;
                User::UpdateColumn("posts",['id','=',$id_post], ["id_log_disc_flow"=>$id_log_disc_flow]); 
                $array_log_group = User::SeachRecordsbyWhere("log_group", "id_log=?", [$id_log_disc_flow],"id, id_group,log_group_json");

                for ($i=0;$i<count($array_log_group);$i++) // цикл по журналам групп 
                {
                    $log_group = json_decode($array_log_group[$i]->log_group_json);
                    $flag=false; $index=0;
                    for ($j=0;$j<count($log_group->attendance_group);$j++) // цикл по записям в журнале (attendance_group)
                    {
                        $name_type_class = User::SearchRecordbyId("type_class", 'short_name', 'id', $request->input('id_type_class'))->short_name;
                        if ($log_group->attendance_group[$j]->date==date('Y-m-d') && $log_group->attendance_group[$j]->type_class==$name_type_class) { $flag=true; $index=$j; break; }  
                    }
                    if ($flag==false)
                    {
                        $listStudents = Group::getListStudentsIdGroup($array_log_group[$i]->id_group);
                        for ($j=0; $j<count($listStudents); $j++) $listStudents[$j]->presence_class="-";

                        $log_group->attendance_group[]=array("date"=>date('Y-m-d'),"type_class"=>"Л","array_students"=>$listStudents);

                        User::UpdateColumn("log_group",['id','=',$array_log_group[$i]->id], ["log_group_json"=>json_encode($log_group)]); 
                    } 
                    else if ($flag==true)
                    {
                        for ($j=0; $j<count($log_group->attendance_group[$index]->array_students); $j++) $log_group->attendance_group[$index]->array_students[$j]->presence_class="-";
                        User::UpdateColumn("log_group",['id','=',$array_log_group[$i]->id], ["log_group_json"=>json_encode($log_group)]); 
                    }
                }
            }
            return response()->json(["info"=>"Создание поста прошло успешно."]);
           
        } 
    }

    public function ResultDeletePost(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            // удаление папки
            $folder = User::SearchRecordbyId("posts",['folder'], 'id',$request->input('id_post'));
            Storage::disk('mypublicdisk')->deleteDirectory($folder->folder);
            
            
            // удаление поста
            User::DeleteRecord("posts","id=?",[$request->input('id_post')]);
            return response()->json(["info"=>"Удаление поста прошло успешно."]); 
        }
    }

    public function ResultEditPost(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            if (trim($request->input('text'))==null) $text=null;
            else $text=trim($request->input('text'));

            // удаление файлов в папке поста
            $folder = User::SearchRecordbyId("posts",['folder'], 'id',$request->input('id_post'));
            $files  = Storage::disk('mypublicdisk')->allFiles($folder->folder);
            Storage::disk('mypublicdisk')->delete($files);
            
            // добавление файлов в папку (если они присутствуют)
            GeneralUserController::UploadFiletoServer($request,$folder->folder,'files'); 

            // обновление данных поста true=1; false=0
            User::UpdateColumn("posts", ['id','=',$request->input('id_post')], ["date_create_post"=>date('Y-m-d H:i:s'),"text"=>$text,"edit"=>1]);

            return response()->json(["info"=>"Редактирование поста прошло успешно."]); 
        }
    }

    public function ListPosts(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            $listposts = User::getListData(User::$ConnectDBWebsite, 'posts', ['id as id_post','id_disc_flow','id_teacher_creator','date_create_post', 'id_type_post',
            'text','attendance_button','date_end_button','edit'],'id_disc_flow', $request->input('id_disc_flow'));
           
            if (count($listposts)==0) return response()->json(["info"=>"На данный момент посты в дисциплине отсутствуют."]);
            else
            {
                $folder = User::getListData(User::$ConnectDBWebsite, 'posts', ['folder'],'id_disc_flow', $request->input('id_disc_flow'));

                for ($i=0;$i<count($listposts);$i++) 
                {
                    $datateacher = User::getListData(User::$ConnectDBWebsite, 'teacher', ['surname','name','patronymic'],'teacher.id',$listposts[$i]->id_teacher_creator,'user',"user.id","teacher.id_user");
                    $listposts[$i]->surname__teacher_creator = $datateacher[0]->surname;
                    $listposts[$i]->name__teacher_creator = $datateacher[0]->name;
                    $listposts[$i]->patronymic__teacher_creator = $datateacher[0]->patronymic;
                    $listposts[$i]->attendance_button=(bool)$listposts[$i]->attendance_button;
                    $listposts[$i]->edit=(bool)$listposts[$i]->edit;

                    $files=Storage::disk('mypublicdisk')->files($folder[$i]->folder);
                    $listposts[$i]->files=[];
                    for ($j=0;$j<count($files);$j++)
                    {
                        $listposts[$i]->files[$j]=Storage::disk('mypublicdisk')->url($files[$j]);
                    }
                }
                return response()->json($listposts);
            }
        }
    }

}
