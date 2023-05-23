<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function ResultCreatePost(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        {  
            // // добавление поста в т. posts true=1; false=0
            // $date = date("Y-m-d H:i:s", strtotime($request->input('date_end_button')));
            // $id_post=User::getIdInsertRecord(["id_disc_flow"=>$request->input('id_disc_flow'),"id_teacher_creator"=>$request->input('id_teacher_creator'),
            // "date_create_post"=>date('Y-m-d H:i:s'),"id_type_post"=>1,"text"=>$request->input('text'), 
            // "attendance_button"=>(int)$request->input('attendance_button'), "date_end_button"=>$date,
            // "id_type_class"=>$request->input('id_type_class'), "date_class"=>date('Y-m-d')], "posts");

            // // создание папки поста
            // $folder = User::SearchRecordbyId("discipline_flow",['folder'], 'id',$request->input('id_disc_flow'));
            // $pathdir = $folder->folder."/posts/postid".$id_post;
            // Storage::disk('mypublicdisk')->makeDirectory($pathdir);

            // User::UpdateColumn("posts",['id','=',$id_post], ["folder"=>$pathdir]);

            if ($request->input('attendance_button') == true)
            {
                // проверить, сущ. ли уже созданный столбец с указанными параметрами по id_disc_flow во всех группах потока
                $id_log_disc_flow = User::SeachRecordsbyWhere("log_disc_flow", "id_disc_flow=? and id_type_log=1", [$request->input('id_disc_flow')],"id")[0]->id;
                $array_log_group = User::SeachRecordsbyWhere("log_group", "id_log=?", [$id_log_disc_flow],"id,log_group_json");

                $array_id_log_group_new_column=null;
                for ($i=0;$i<count($array_log_group);$i++) // цикл по журналам групп 
                {
                    $log_group = json_decode($array_log_group[$i]->log_group_json);
                    $flag=false;
                    for ($j=0;$j<count($log_group->attendance_group);$j++) // цикл по записям в журнале (attendance_group)
                    {
                        $name_type_class = User::SearchRecordbyId("type_class", 'short_name', 'id', $request->input('id_type_class'))->short_name;
                        if ($log_group->attendance_group[$j]->date==date('Y-m-d') && $log_group->attendance_group[$j]->type_class==$name_type_class) { $flag=true; break; }  
                    }
                    if ($flag==false) $array_id_log_group_new_column[]=$array_log_group[$i]->id; // если записей не нашлось в журнале, сохр. id для будущего добавления нового столбца
                }

                return response()->json($array_id_log_group_new_column);

                if ($array_id_log_group_new_column!=null)
                {
                    // добавить новый столбец с типом + сегодняшней датой + студентами группы в журнал групп потока конкретной дисциплины

                    // получить массив студентов по id_group
                }
                

            }
            // return response()->json(["info"=>"Добавление объявления прошло успешно."]);
           
        } 
    }
}
