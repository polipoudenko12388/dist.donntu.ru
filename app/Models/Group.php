<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public static $ConnectDBUniv="mysql";
    public static $ConnectDBWebsite="mysql2";

    // список групп с инфой по ним (институт, кафедра и тд)
    public static function getListGroupInfo($id_institute, $id_faculty, $id_department, $namegroup)
    {
        $result = DB::table('dbwebsite_university.group')
        ->select('dbwebsite_university.group.id as id_group_db_website', 'dbwebsite_university.group.name as name_group', 
        'dbuniversity.institute.name as institute', 'dbuniversity.faculty.name as faculty', 'dbuniversity.department.name as department')
        ->join('dbuniversity.groups', 'dbwebsite_university.group.id_group_db_univ','=','dbuniversity.groups.id')
        ->join('dbuniversity.general_info_group', 'groups.id_general_info_group','=','general_info_group.id')
        ->join('dbuniversity.info_inst_facul_depart', 'info_inst_facul_depart.id','=','general_info_group.id_info_ifd')
        ->join('institute', 'institute.id','=','info_inst_facul_depart.id_institute')
        ->join('faculty', 'faculty.id','=','info_inst_facul_depart.id_faculty')
        ->join('department', 'department.id','=','info_inst_facul_depart.id_department')
        ->when($id_institute, function ($query, $id_institute)
        {
            $query->where('institute.id', $id_institute);
        })
        ->when($id_faculty, function ($query, $id_faculty)
        {
            $query->where('faculty.id', $id_faculty);
        })
        ->when($id_department, function ($query, $id_department)
        {
            $query->where('department.id', $id_department);
        })
        ->when($namegroup, function ($query, $namegroup)
        {
            $query->where('dbwebsite_university.group.name', 'like', '%'.$namegroup.'%');
        })
        ->get();
        return $result;
    }

    // список студентов по группе
    public static function getListStudentsIdGroup($id_group)
    {
        $result = DB::connection(Group::$ConnectDBWebsite)->table('group_student')
        ->select('group_student.id_student', 'surname', 'name', 'patronymic')
        ->join('student', 'group_student.id_student','=','student.id')
        ->join('user', 'student.id_user','=','user.id')
        ->where('group_student.id_group', $id_group)
        ->get();
        return $result;
    }

    public static function getListFacultyorDepartment($nametable, $arraynamecolumnprint, $joinfirst, $jointwo,$id_institute,$id_faculty,$id_department=null)
    {
        $result = DB::connection(Group::$ConnectDBUniv)->table($nametable)
        ->distinct()
        ->select($arraynamecolumnprint)
        ->join('info_inst_facul_depart', $joinfirst,'=',$jointwo)
        ->when($id_institute, function ($query, $id_institute)
        {
            $query->where('info_inst_facul_depart.id_institute', $id_institute);
        })
        ->when($id_faculty, function ($query, $id_faculty)
        {
            $query->where('info_inst_facul_depart.id_faculty', $id_faculty);
        })
        ->when($id_department, function ($query, $id_department)
        {
            $query->where('info_inst_facul_depart.id_department', $id_department);
        })->get();
        return $result;
    }
}
