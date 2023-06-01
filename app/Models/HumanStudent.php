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

    // получения данных о студенте (для профиля)
    public function getDataStudents($id_human)
    {
        $DataHuman = parent::getDataHuman($id_human);

        $DataStudent = DB::connection($this->ConnectDBUniv)->table('human')
        ->selectraw("student.data_start_education,status.name as status, groups.name as name_group, 
         form_education.name as name_form_education, institute.name as institute, faculty.name as faculty,department.name as department,direction.id_direction,
        direction.name as direction,direction.profile,educationalprogram.name as educationalprogram, 
        TIMESTAMPDIFF(YEAR, student.data_start_education, CURDATE())+1 as course")
        ->join('student', 'human.id','=','student.id_human')
        ->join('status', 'status.id','=','student.id_status')
        ->join('groups', 'groups.id','=','student.id_group')
        ->join('form_education', 'form_education.id','=','groups.id_form_education')
        ->join('general_info_group', 'general_info_group.id','=','groups.id_general_info_group')
        ->join('info_inst_facul_depart', 'info_inst_facul_depart.id','=','general_info_group.id_info_ifd')
        ->join('institute', 'institute.id','=','info_inst_facul_depart.id_institute')
        ->join('faculty', 'faculty.id','=','info_inst_facul_depart.id_faculty')
        ->join('department', 'department.id','=','info_inst_facul_depart.id_department')
        ->join('direction', 'direction.id','=','general_info_group.id_direction')
        ->join('educationalprogram', 'educationalprogram.id','=','direction.id_educ_prog')
        ->where(function ($query)  use (&$id_human) {$query->where('id_human',$id_human)->where('id_status', 1); })->get();

        return array_merge((array)$DataHuman, array("array_data_student"=>$DataStudent));
    }

}
