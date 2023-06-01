<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    public static $ConnectDBUniv="mysql";
    public static $ConnectDBWebsite="mysql2";
    
     // поиск user/teacher/student по его id_human_db_univ/id_user in table User/teacher/student
     public static function SearchRecordbyId($nametable, $arraynamecolumnprint, $namecolumnsql, $value_sql)
     {
        return DB::connection(User::$ConnectDBWebsite)->table($nametable)->select($arraynamecolumnprint)->where($namecolumnsql, $value_sql)->first();
     }

    // поиск user-преподавателя/студента по id student_db_univ|workes__db_univ  в т. position_teacher/group_position
    public static function getCountUserTeacherorStudent($array_value_sql_id, $nametable, $namecolumnsql)
    {
        return DB::connection(User::$ConnectDBWebsite)->table($nametable)->whereIn($namecolumnsql, $array_value_sql_id)->count();
    }

    // поиск записей по условиям whereRaw
    public static function SeachRecordsbyWhere($nametable, $strwhereRaw, $array_value_sql_raw=null,$strnamecolumnprintsql=null)
    {
        return DB::connection(User::$ConnectDBWebsite)->table($nametable)
        ->when($strnamecolumnprintsql, function ($query, $strnamecolumnprintsql)
        { 
            $query->selectRaw($strnamecolumnprintsql);
        })
        ->whereRaw($strwhereRaw, $array_value_sql_raw)->get();
    }


     // получить основную информацию о пользователе (ФИО, email, phone)
     public static function getDataObject($nametable, $arraynamecolumnprint, $tablejoin, $firstjoin,$secondjoin, $namecolumnsql, $value_sql)
     {
         $result = DB::connection(Discipline::$ConnectDBWebsite)->table($nametable)
         ->select($arraynamecolumnprint)
         ->join($tablejoin, $firstjoin,'=',$secondjoin)
         ->where($namecolumnsql, $value_sql)
         ->get();    
         return $result;
     }

      // список данных таблицы (ролей/институтов/факультетов/кафедр и тд)
      public static function getListData($connect, $nametable, $arraynamecolumnprint, $namecolumnsql=null, $value_sql=null,$tablejoin=null, $firstjoin=null,$secondjoin=null)
      {
        return DB::connection($connect)->table($nametable)
        ->select($arraynamecolumnprint)
        ->when($tablejoin, function ($query, $tablejoin) use(&$firstjoin, &$secondjoin) 
        { 
            $query->distinct();
            $query->join($tablejoin, $firstjoin,'=',$secondjoin);
        })
        ->when($value_sql, function ($query, $value_sql) use(&$namecolumnsql) 
        { 
            $query->where($namecolumnsql, $value_sql);
        })->get();
      }

    public static function sqlraw($nametable, $arraysql)
    {
        return DB::table($nametable)->select(DB::raw($arraysql))->get();
    }
    // добавление записи в таблицу с получением его id
    public static function getIdInsertRecord($array_insert_value, $nametable)
    {
        return DB::connection(User::$ConnectDBWebsite)->table($nametable)->insertGetId($array_insert_value);
    }

    // добавление записей(1 или n) в таблицу (без получения id)
    public static function InsertRecord($array_insert_value, $nametable)
    {
        DB::connection(User::$ConnectDBWebsite)->table($nametable)->insert($array_insert_value);
    } 

    // обновление записей
    public static function UpdateColumn($nametable, $arraycolumnsql, $array_update_value)
    {
        DB::connection(User::$ConnectDBWebsite)->table($nametable)->where([$arraycolumnsql])->update($array_update_value);
    }

      // обновление записей
      public static function UpdateColumnJson($nametable, $strwhereRaw, $array_value_sql_raw, $name_update_column, $str_update_value)
      {
        DB::connection(User::$ConnectDBWebsite)->table($nametable)->whereRaw($strwhereRaw, $array_value_sql_raw)->update([$name_update_column => DB::raw($str_update_value)]);
      }

    public static function DeleteRecord($nametable,$strwhereRaw, $array_value_sql_raw,$namecolumnsql=null, $array_delete_id_value=null)
    {
        return DB::connection(User::$ConnectDBWebsite)->table($nametable)
        ->when($array_value_sql_raw, function ($query, $array_value_sql_raw) use (&$strwhereRaw)
        {
            $query->whereRaw($strwhereRaw, $array_value_sql_raw);
        })
        ->when($array_delete_id_value, function ($query, $array_delete_id_value) use (&$namecolumnsql)
        {
            $query->whereIn($namecolumnsql, $array_delete_id_value);
        })
        ->delete();
    }
}
