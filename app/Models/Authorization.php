<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Authorization extends Model
{
    private static $ConnectDBWebsite="mysql2";

    // проверка при авторизации 
    public static function VerificationUser($email_login, $password)
    {
        $result = DB::connection(Authorization::$ConnectDBWebsite)->table('registration')
        ->select('id as id_reg','id_user','id_role_user')
        ->where(function ($query)  use (&$email_login)
        {
            $query->where('email',$email_login)->orWhere('login', $email_login);
        })
        ->where('password',$password)->first();
        return $result;
    }

    // группировка по столбцу 
    public static function OrderByRecords($nametable, $arrayconditions,$columnsNotNull, $namecolumnOrderby)
    {
        return DB::connection(Authorization::$ConnectDBWebsite)->table($nametable)->where([$arrayconditions])->whereNotNull($columnsNotNull)->orderBy($namecolumnOrderby)->first();
    }
}
