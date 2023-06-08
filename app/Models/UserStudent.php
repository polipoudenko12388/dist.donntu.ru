<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class UserStudent extends Model
{
    // группа студента, которая входит в поток конкретной дисциплины
    public static function getGroupStudent($id_student, $id_disc_flow)
    {
        $result = DB::connection(UserTeacher::$ConnectDBWebsite)->table('discipline_flow')
        ->select('group_student.id_group')
        ->join('flow_group', 'flow_group.id_flow','=','discipline_flow.id_flow')
        ->join('group_student', 'group_student.id_group','=','flow_group.id_group')
        ->where(function ($query)  use (&$id_student, &$id_disc_flow)
        {
            $query->where('group_student.id_student', $id_student);
            $query->where('discipline_flow.id', $id_disc_flow);  
        })->first();    
        return $result;
    }
}
