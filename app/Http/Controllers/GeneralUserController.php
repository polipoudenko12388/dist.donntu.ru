<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class GeneralUserController extends Controller
{
    // проверка токена
    public static function VerifactionToken($id_user_reg, $token)
    {
        return User::SeachRecordsbyWhere("tokens_authorization", "id_registration=? and token=?", [$id_user_reg,$token]);
    }

    public function exit(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else
        {
            User::UpdateColumn("tokens_authorization", ['id','=',$token_verification[0]->id], ["data_save_note"=>date('Y-m-d'),'token'=>null]);
            return ["info" => "Вы вышли из системы."];
        }
    }
}
