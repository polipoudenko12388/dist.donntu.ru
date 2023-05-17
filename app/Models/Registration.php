<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    private static $ConnectDBWebsite="mysql2";

    // проверка email/login при регистрации
    public static function VerificationEmailLogin($email, $login)
    {
        $result = DB::connection(Registration::$ConnectDBWebsite)->table('registration')
        ->where('email', $email) ->orWhere('login', $login)->count();
        return $result;
    }
}
