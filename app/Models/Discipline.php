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
        ->where('discipline_flow.id_list_discipline', $id_list_disc)
        ->where('discipline_flow.id_flow', $id_flow)
        ->get();    
        return $result;
    }

    public static function getListDisciplineStudent($id_student,$namedisc,$id_disc_flow)
    {
        $result = DB::connection(Discipline::$ConnectDBWebsite)->table('group_student')
        ->select('list_disciplines.id as id_disc', 'flow_group.id_flow', 'flow.name as name_flow',  'discipline_flow.id as id_disc_flow', 
        'list_disciplines.name as name_disc', 'list_disciplines.id_institute_db_univ', 'list_disciplines.id_faculty_db_univ',  'list_disciplines.id_department_db_univ',
        'list_disciplines.fon', 'list_disciplines.id_teacher as id_teacher_creator')
        ->join('flow_group', 'flow_group.id_group','=','group_student.id_group')
        ->join('flow', 'flow.id','=','flow_group.id_flow')
        ->join('discipline_flow', 'discipline_flow.id_flow','=','flow_group.id_flow')
        ->join('list_disciplines', 'discipline_flow.id_list_discipline','=','list_disciplines.id')
        ->where(function ($query)  use (&$namedisc, &$id_student,  &$id_disc_flow)
         {
            $query->when($namedisc, function ($query, $namedisc)
            {
                $query->where('list_disciplines.name', 'like', '%'.$namedisc.'%');
            });
            $query->when($id_disc_flow, function ($query, $id_disc_flow)
            {
                $query->where('discipline_flow.id', $id_disc_flow);
            });
            $query->where('group_student.id_student', $id_student);
        })->get();    
        return $result;
    }
}
