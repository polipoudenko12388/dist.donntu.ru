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
}
