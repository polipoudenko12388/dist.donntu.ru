<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Discipline extends Model
{
    public static $ConnectDBWebsite="mysql2";

    public static function getListTeachersFlow($id_list_disc,$id_flow)
    {
        $result = DB::connection(Discipline::$ConnectDBWebsite)->table('discipline_flow')
        ->select('list_disciplines.id_teacher as id_creator','discipline_flow.id as id_disc_flow', 'flow.name as name_flow',
         'number_hours_reading',  'teacher_discipline.id_teacher')
        ->leftJoin('list_disciplines', 'list_disciplines.id','=','discipline_flow.id_list_discipline')
        ->leftJoin('teacher_discipline', 'discipline_flow.id','=','teacher_discipline.id_discipline_flow')
        ->join('flow', 'discipline_flow.id_flow','=','flow.id')
        // ->join('teacher', 'teacher.id','=','teacher_discipline.id_teacher')
        // ->join('user', 'user.id','=','teacher.id_user')
        ->where('discipline_flow.id_list_discipline', $id_list_disc)
        ->where('discipline_flow.id_flow', $id_flow)
        ->get();    
        return $result;
    }
}
