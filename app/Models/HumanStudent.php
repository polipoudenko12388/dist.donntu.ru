<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Human;
use Illuminate\Support\Facades\DB;

class HumanStudent extends Human
{
    // поиск человека по id_human in table student
    public function SearchStudents($id_human)
    {
        $result = DB::connection($this->ConnectDBUniv)->table('student')
            ->select('id','id_group')
            ->where(function ($query)  use (&$id_human)
            {
                $query->where('id_human',$id_human)->where('id_status', 1);
            })->get();
        return $result;
    }
}
