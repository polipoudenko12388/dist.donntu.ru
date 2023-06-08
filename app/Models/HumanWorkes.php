<?php

namespace App\Models;

use App\Models\Human;
use Illuminate\Support\Facades\DB;

class HumanWorkes extends Human
{

    // private $ConnectDBUniv="mysql";
    
    // поиск человека по id_human in table Workes
    public function SearchWorkes($id_human, $id_role_workes)
    {
        $result = DB::connection($this->ConnectDBUniv)->table('workes')
            ->select('id')
            ->where('id_human',$id_human)
            ->where(function ($query)  use (&$id_role_workes)
            {
                $query->where('id_role',$id_role_workes)->where('id_status_workes', 5);
            })->get();
        return $result;
    }

    // получения данных о работнике (для профиля)
    public function getDataWorkes($id_human, $id_role_workes)
    {
        $DataHuman = parent::getDataHuman($id_human);

        $DataWorkes = DB::connection($this->ConnectDBUniv)->table('human')
        ->select('status.name as status', 'position.name as position','institute.name as institute','faculty.name as faculty', 'department.name as department')
        ->rightjoin('workes', 'human.id','=','workes.id_human')
        // ->join('role', 'role.id','=','workes.id_role')
        ->join('status', 'status.id','=','workes.id_status_workes')
        ->join('position', 'position.id','=','workes.id_position')
        ->join('info_inst_facul_depart', 'info_inst_facul_depart.id','=','workes.id_info_ifd_x')
        ->join('institute', 'institute.id','=','info_inst_facul_depart.id_institute')
        ->join('faculty', 'faculty.id','=','info_inst_facul_depart.id_faculty')
        ->join('department', 'department.id','=','info_inst_facul_depart.id_department')
        ->where('id_human',$id_human)
        ->where(function ($query)  use (&$id_role_workes)
        {
            $query->where('id_role',$id_role_workes)->where('id_status_workes', 5);
        })->get();

        return array_merge((array)$DataHuman, array("array_data_workes"=>$DataWorkes));
    }

}
