<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class UserTeacher extends User
{
    public static $ConnectDBWebsite="mysql2";

    // массив дисциплин преподавателя и потоков, за которыми он закреплен (как создатель или просто добавленный)
    public static function getArrayDisciplineFlowTeacher(string $strnamecolumnprint, $strwhereRaw, $array_value_sql_raw,$namedisc=null, $id_disc=null)
    {
        $result = DB::connection(UserTeacher::$ConnectDBWebsite)->table('list_disciplines')
        ->selectRaw($strnamecolumnprint)
        ->leftJoin('discipline_flow', 'discipline_flow.id_list_discipline','=','list_disciplines.id')
        ->leftJoin('flow', 'discipline_flow.id_flow','=','flow.id')
        ->leftJoin('teacher_discipline', 'teacher_discipline.id_discipline_flow','=','discipline_flow.id')
         ->where(function ($query)  use (&$namedisc, &$strwhereRaw, &$array_value_sql_raw, &$id_disc)
         {
            $query->when($namedisc, function ($query, $namedisc)
            {
                $query->where('list_disciplines.name', 'like', '%'.$namedisc.'%');
            });
            $query->when($id_disc, function ($query, $id_disc)
            {
                $query->where('list_disciplines.id', $id_disc);
            });
            $query->whereRaw($strwhereRaw, $array_value_sql_raw);  
        })->get();    

        return $result;
    }
}
