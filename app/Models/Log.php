<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    public static $ConnectDBWebsite="mysql2";

    // поиск потока в дисциплине для определения того, сущ ли его группы в журнале (для уд. групп в log_group или добавления новых)
    public static function SearchGroupsFlowinLog($arraynamecolumnprint,$id_flow, $groups=null, $distinctraw=null)
    {
        $result = DB::connection(Discipline::$ConnectDBWebsite)->table('discipline_flow')
        ->when($distinctraw, function ($query) { $query->distinct();})
        ->select($arraynamecolumnprint)
        ->join('log_disc_flow', 'discipline_flow.id','=','log_disc_flow.id_disc_flow')
        ->join('log_group', 'log_disc_flow.id','=','log_group.id_log')
        ->where('discipline_flow.id_flow', $id_flow)
        ->when($groups, function ($query, $groups) 
        { 
            $query->whereIn('log_group.id_group', $groups);
        })
        ->get();    
        return $result;
    }

    // журнал успеваемости студента
    public static function getGradebookStudent($id_student,$id_disc_flow)
    {
        $result = DB::connection(Discipline::$ConnectDBWebsite)->table('discipline_flow')
        ->select('discipline_flow.id as id_disc_flow', 'log_group.log_group_json','log_group.id_group')
        ->join('flow_group', 'discipline_flow.id_flow','=','flow_group.id_flow')
        ->join('group_student', 'group_student.id_group','=','flow_group.id_group')
        ->join('log_disc_flow', 'log_disc_flow.id_disc_flow','=','discipline_flow.id')
        ->join('log_group', 'log_group.id_log','=','log_disc_flow.id')
        ->where(function ($query)  use (&$id_student,  &$id_disc_flow)
         {
            $query->where('group_student.id_student', $id_student);
            $query->where('discipline_flow.id', $id_disc_flow);
            $query->where('log_disc_flow.id_type_log', 2);
            $query->whereRaw('log_group.id_group = flow_group.id_group');
         })->get();    
        return $result;
    }
}
