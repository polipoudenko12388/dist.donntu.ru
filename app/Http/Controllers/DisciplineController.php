<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use App\Models\Discipline;
use Illuminate\Support\Facades\Storage;
use App\Models\UserTeacher;
use App\Models\Group;

class DisciplineController extends Controller
{

    // список дисциплин с потоками, за которыми закреплен преподаватель (создатель дисциплины или закрепленный)
    public function ListDisciplines(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else
        {
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            $array_disc_teacher = UserTeacher::getArrayDisciplineFlowTeacher("Distinct list_disciplines.id as id_disc, list_disciplines.name as name_disc, 
            list_disciplines.id_teacher as id_teacher_creator, fon, id_institute_db_univ, id_faculty_db_univ, id_department_db_univ", 
            "(list_disciplines.id_teacher=? or teacher_discipline.id_teacher=?)", 
            [$arrayinfotoken->id_teacher_student,$arrayinfotoken->id_teacher_student], $request->input("name_disc"),$request->input("id_disc"));
            
            $ListDisciplinesFlow = [];
            for ($i=0; $i<count($array_disc_teacher); $i++) 
            { 
                $ListDisciplinesFlow[$i]=array("id_disc"=>$array_disc_teacher[$i]->id_disc, "name_disc"=>$array_disc_teacher[$i]->name_disc, "id_teacher_creator"=>$array_disc_teacher[$i]->id_teacher_creator, 
                "fon"=>(Storage::disk('mypublicdisk')->url($array_disc_teacher[$i]->fon)), "id_institute"=>$array_disc_teacher[$i]->id_institute_db_univ, "id_faculty"=>$array_disc_teacher[$i]->id_faculty_db_univ, 
                "id_department"=>$array_disc_teacher[$i]->id_department_db_univ);
                if ($array_disc_teacher[$i]->id_teacher_creator == $arrayinfotoken->id_teacher_student) $ListDisciplinesFlow[$i]["edit_disc"]=true; 
                else $ListDisciplinesFlow[$i]["edit_disc"]=false;

                $array_flow_disc = UserTeacher::getArrayDisciplineFlowTeacher("Distinct discipline_flow.id_flow, flow.name as name_flow", 
                "(list_disciplines.id_teacher=? or teacher_discipline.id_teacher=?) and list_disciplines.id=?", 
                [$arrayinfotoken->id_teacher_student,$arrayinfotoken->id_teacher_student, $array_disc_teacher[$i]->id_disc]);
                    
                if ($array_flow_disc[0]->id_flow==null) $ListDisciplinesFlow[$i]["array_flow"]=null;
                else
                {
                    for ($j=0; $j<count($array_flow_disc);$j++)
                    {
                        $ListDisciplinesFlow[$i]["array_flow"][$j] = array("id_flow"=>$array_flow_disc[$j]->id_flow, "name_flow"=>$array_flow_disc[$j]->name_flow);
                    }
                }
            }
            
            return response()->json($ListDisciplinesFlow);
        }   
    }

    public function ResultCreateDisc(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            // проверка, сущ. ли дисциплина с таким же именем, как добавляемая
            $Seachdisc =  User::SeachRecordsbyWhere("list_disciplines", "name=?", [$request->input('name_disc')]);
            if(count($Seachdisc)>0)  return response()->json(["error"=>"Дисциплина с таким именем уже существует."]);
            else if (trim($request->input('name_disc'))==null)  return response()->json(["error"=>"Добавьте название дисциплины перед ее созданием."]);
            else
            {
                if ($request->input('id_faculty')==null || $request->input('id_institute')==null || $request->input('id_department')==null) return response()->json(["error"=>"Не совершен выбор института/факультета/кафедры."]);
                else 
                {
                // добавление дисциплины в т. list_disciplines
                $id_newdisc = User::getIdInsertRecord(array("name"=>$request->input('name_disc'), "id_institute_db_univ"=>$request->input('id_institute'), "id_faculty_db_univ"=>$request->input('id_faculty'),
                "id_department_db_univ"=>$request->input('id_department'), "id_teacher"=>$arrayinfotoken->id_teacher_student, "fon"=>"defaultimage/default_fon_discipline.png"),"list_disciplines");

                // создание папки дисциплины
                $pathdir = $this->createpathdir("disciplines/",$id_newdisc, $request->input('name_disc'));
                Storage::disk('mypublicdisk')->makeDirectory($pathdir);
                User::UpdateColumn("list_disciplines",['list_disciplines.id','=',$id_newdisc], ["list_disciplines.folder"=>$pathdir]);
                return response()->json(["info"=>"Добавление дисциплины прошло успешно."]);
                }
            }
        }
    }

    private function createpathdir($path,$id_disc, $namedir)
    {
        return $path."id".$id_disc.File::translit($namedir).date('_Ymd_His');
    }

    public function ResultDeleteDisc(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            // удаление папки
            $folder = User::SearchRecordbyId("list_disciplines",['folder'], 'id',$request->input('id_disc'));
            Storage::disk('mypublicdisk')->deleteDirectory($folder->folder);
            
            // удаление дисциплины
            User::DeleteRecord("list_disciplines","id=?",[$request->input('id_disc')]);
            return response()->json(["info"=>"Удаление дисциплины прошло успешно."]); 
        }
    }

    public function ResultEditDisc(Request $request)
    {
         // проверка токена
         $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
         if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
         else  
         { 
             // проверка, сущ. ли дисциплина с таким же именем, как изменяемая
             $Seachdisc =  User::SeachRecordsbyWhere("list_disciplines", "name=? and id!=?", [$request->input('new_name'),$request->input('id_disc')]);
             if(count($Seachdisc)>0)  return response()->json(["error"=>"Дисциплина с таким именем уже существует."]);
             else
             {
                if ($request->input('id_faculty')==null || $request->input('id_institute')==null || $request->input('id_department')==null)  return response()->json(["error"=>"Не совершен выбор института/факультета/кафедры."]);
                else 
                {
                    //  изменение имени папки дисциплины
                    $folder = User::SearchRecordbyId("list_disciplines",['folder'], 'id',$request->input('id_disc'));
                    $newpathdir = $this->createpathdir("disciplines/",$request->input('id_disc'), $request->input('new_name'));
                    Storage::disk('mypublicdisk')->move($folder->folder, $newpathdir);
                        
                    // обновление данных дисциплины
                    User::UpdateColumn("list_disciplines", ['list_disciplines.id','=',$request->input('id_disc')], 
                    ["name"=>$request->input('new_name'),"id_institute_db_univ"=>$request->input('id_institute'),
                    "id_faculty_db_univ"=>$request->input('id_faculty'),"id_department_db_univ"=>$request->input('id_department'),"folder"=>$newpathdir]);
                
                    if ($request->input('id_new_creator')!=null)
                    {
                        User::UpdateColumn("list_disciplines", ['list_disciplines.id','=',$request->input('id_disc')],  ["id_teacher"=>$request->input('id_new_creator')]);
                    }
                    return response()->json(["info"=>"Редактирование дисциплины прошло успешно."]);  
                }
             } 
         }
    }

    public function ResultAddFlowinDisc(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            // проверка, есть ли такой поток в дисциплине
            $SeachFlow =  User::SeachRecordsbyWhere("discipline_flow", "id_list_discipline=? and id_flow=?", [$request->input('id_disc'),$request->input('id_flow')]);
            if(count($SeachFlow)>0)  return response()->json(["error"=>"Поток в данную дисциплину уже добавлен."]);
            else
            {
                // добавление потока в дисциплину
                $id_discflow = User::getIdInsertRecord(array("id_list_discipline"=>$request->input('id_disc'), "number_hours_reading"=>$request->input('number_hours_reading'),
                "id_flow"=>$request->input('id_flow')),"discipline_flow");

                // создание папки потока дисциплины
                $folder = User::SearchRecordbyId("list_disciplines",['folder'], 'id',$request->input('id_disc'));
                $pathdir = $this->createpathdir($folder->folder."/",$id_discflow, $request->input('name_flow'));
                Storage::disk('mypublicdisk')->makeDirectory($pathdir);
                
                User::UpdateColumn("discipline_flow",['discipline_flow.id','=',$id_discflow], ["folder"=>$pathdir]);

                // создание журнала посещаемости
                $this->createlog(1,$id_discflow,$request->input('id_flow'));

                // создание журнала успеваемости
                $this->createlog(2,$id_discflow,$request->input('id_flow'));
                
                if ($request->input('teachers')!=null)
                {
                    // добавление преподавателей в поток дисциплины
                    User::InsertRecord(array_map(function ($object)  use (&$id_discflow) { return array("id_discipline_flow" => $id_discflow, "id_teacher" => $object); },  
                    $request->input('teachers')), "teacher_discipline");
                }
                return response()->json(["info"=>"Добавление потока в дисциплину прошло успешно."]);
            }

        }
    }

    public function ResultDeleteFlowinDisc(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            // удаление папки
            $data_disc_flow = User::SeachRecordsbyWhere("discipline_flow", "id=?", [$request->input('id_disc_flow')]);
            Storage::disk('mypublicdisk')->deleteDirectory($data_disc_flow[0]->folder);
             
            // удаление потока дисциплины
            User::DeleteRecord("discipline_flow","id=?",[$data_disc_flow[0]->id]);
            return response()->json(["info"=>"Удаление потока дисциплины прошло успешно."]);  
        }
    }

    public function ListTeachersFlowinDisc(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            $bufdataflow = Discipline::getListTeachersFlow($request->input('id_disc'),$request->input('id_flow'));
            $flow=array("id_disc_flow"=>$bufdataflow[0]->id_disc_flow, "name_flow"=>$bufdataflow[0]->name_flow,"number_hours_reading"=>$bufdataflow[0]->number_hours_reading);
           
            if ($bufdataflow[0]->id_teacher!=null) $count=count($bufdataflow)+1;
            else $count=count($bufdataflow);
            for ($i=0;$i<$count;$i++)
            {
                if ($i==0)  { $id_teacher = $bufdataflow[$i]->id_creator; $creator=true; }
                else        { $id_teacher = $bufdataflow[$i-1]->id_teacher; $creator=false; }
                    
                $buf = User::getDataObject("teacher", ['surname','name','patronymic','email','phone'], "user", "user.id",'teacher.id_user',  "teacher.id", $id_teacher);
                $flow['arrayteacher'][$i]=array("id_teacher"=>$id_teacher,"surname"=>$buf[0]->surname, "name"=>$buf[0]->name,
                "patronymic"=>$buf[0]->patronymic,"email"=>$buf[0]->email,"phone"=>$buf[0]->phone,"creator"=>$creator);
            }
            return response()->json($flow);
        }
    }

    public function ResultEditFlowinDisc(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {
            // обновление данных потока дисциплины
            if ($request->input('number_hours_reading')!=null)
            {
                User::UpdateColumn("discipline_flow", ['discipline_flow.id','=',$request->input('id_disc_flow')], 
                ["number_hours_reading"=>$request->input('number_hours_reading')]);
            }
            if ($request->input('add_teachers')!=null)
            {
                // добавление преподавателей в поток дисциплины
                User::InsertRecord(array_map(function ($object)  use (&$request) 
                { return array("id_discipline_flow" => $request->input('id_disc_flow'), "id_teacher" => $object); },  $request->input('add_teachers')), "teacher_discipline");
            }

            if ($request->input('delete_teachers')!=null)
            {
                // удаление преподавателей из потока дисциплины
                User::DeleteRecord("teacher_discipline","id_discipline_flow=?",$request->input('id_disc_flow'),'id_teacher',$request->input('delete_teachers'));
            }

            return response()->json(["info"=>"Редактирование потока дисциплины прошло успешно."]);  
        }
    }

    // создание журнала - временное 
    public function createlogbuf(Request $request)
    {
        return response()->json($this->createlog(2,$request->input('id_disc_flow'),$request->input('id_flow')));
    }

    // создание журнала (посещаемости или успеваемости)
    public function createlog($id_type_log,$id_disc_flow,$id_flow)
    {
        // добавление в log_disc_flow
        $id_log = User::getIdInsertRecord(array("id_disc_flow"=>$id_disc_flow,"id_type_log"=>$id_type_log),"log_disc_flow");
        // получение списка групп потока
        $groups_flow = User::getDataObject("flow", ['id_group'], "flow_group", "flow.id",'flow_group.id_flow',  "flow.id", $id_flow);
    
        if (count($groups_flow)>0) DisciplineController::addinLog_group($groups_flow, $id_type_log, $id_log);
    }

    public static function addinLog_group($groups_flow, $id_type_log, $id_log)
    {
        for ($i=0; $i<count($groups_flow); $i++)
        {
            $name_group = User::SearchRecordbyId("group", 'name', "id", $groups_flow[$i]->id_group);
            $listStudents = Group::getListStudentsIdGroup($groups_flow[$i]->id_group);
            if ($id_type_log==1)
            {
                for ($j=0; $j<count($listStudents); $j++) $listStudents[$j]->presence_class="-";
                $file_log_group_json=File::create_file_log_attend_group_json($groups_flow[$i]->id_group,$name_group->name,$listStudents);
            }
            else if ($id_type_log==2)
            {
                $array_students_types_control=$listStudents->map(function ($object) { return clone $object; }); 
                $array_students_intersessional_control=$listStudents->map(function ($object) { return clone $object; }); 
                 $array_students_passes=$listStudents->map(function ($object) { return clone $object; }); 
                for ($j=0; $j<count($listStudents); $j++) 
                {
                    $array_students_types_control[$j]->score=null; // оценка
                    $array_students_types_control[$j]->date=null; // дата сдачи
                }
                for ($j=0; $j<count($listStudents); $j++) 
                {
                    $array_students_intersessional_control[$j]->passage=null; // прохождение МСК
                    $array_students_intersessional_control[$j]->date=null;
                }
                for ($j=0; $j<count($listStudents); $j++) 
                {
                    $array_students_passes[$j]->count=null; $array_students_passes[$j]->date=null;
                }
                $array_students_offset = $array_students_types_control->map(function ($object) { return clone $object; }); 
                $array_students_exam   = $array_students_types_control->map(function ($object) { return clone $object; }); 

                $file_log_group_json=File::create_file_gradebook($name_group->name,$array_students_types_control, $array_students_intersessional_control, 
                $array_students_passes,$array_students_offset,$array_students_exam);
            }
            User::InsertRecord(array("id_log"=>$id_log, "id_group" => $groups_flow[$i]->id_group, "log_group_json"=>$file_log_group_json), "log_group");
        }
    }
}

