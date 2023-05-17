<?php

namespace App\Http\Controllers;

use App\Models\Authorization;
use Illuminate\Http\Request;
use App\Models\User;

class AuthorizationController extends Controller
{
    
    // проверка авторизации
    public function Authorizationcheck(Request $request)
    {
        // данные об зарегистр. пользователе
        $user_reg = Authorization::VerificationUser($request->input('email_login'),$request->input('password'));
        if (empty($user_reg)) return response()->json(["error" => "Неправильно указан логин и/или пароль."]);
        else 
        {
            if ($user_reg->id_role_user == 5)  $id_teacher_student=User::SearchRecordbyId("teacher","id", "id_user", $user_reg->id_user);
            else if ($user_reg->id_role_user == 1) $id_teacher_student=User::SearchRecordbyId("student", "id", "id_user", $user_reg->id_user);
            $jwt = $this->createToken($user_reg->id_user, $id_teacher_student->id, $user_reg->id_role_user);
            
            // поиск сущ. записи о ранней авторизации на тек. устройстве (т.е. если раньше человек уже заходил на платформу через тек. устройство, но сейчас token=null || not null, браузер очистился или тд)
            $past_author_records = User::SeachRecordsbyWhere("tokens_authorization", "id_registration=? and platform=? and mobile=? and brand=? and version_brand=?", 
            [$user_reg->id_reg, $request->input('platform'),$request->input('mobile'),$request->input('brand'),$request->input('version_brand')]);

            if (count($past_author_records) > 0) {User::UpdateColumn("tokens_authorization", ['id','=',$past_author_records[0]->id], ["data_save_note"=>date('Y-m-d'),'token'=>$jwt]);  }
            else
            {
                // добавление записи об авторизации в tokens_authorization; true=1; false=0
                User::InsertRecord(array("id_registration"=>$user_reg->id_reg, "platform"=>$request->input('platform'),"mobile"=>(int)($request->input('mobile')),
                "brand"=>$request->input('brand'),"version_brand"=>$request->input('version_brand'),"data_save_note"=>date('Y-m-d'),"token"=>$jwt), "tokens_authorization");
            }
            // проверка, что user авторизован не более 5 раз, иначе самую старую авторизацию null
            $count_author_records_user = User::SeachRecordsbyWhere("tokens_authorization", "id_registration=? and token is not null", [$user_reg->id_reg]);
            if (count($count_author_records_user)>5)
            {
                // поиск самой старой записи
                $old_record_author = Authorization::OrderByRecords("tokens_authorization",['id_registration','=',$user_reg->id_reg],"token", "data_save_note");
                // обнуление token самой старой записи (или 1)
                User::UpdateColumn("tokens_authorization", ['id','=',$old_record_author->id], ["data_save_note"=>date('Y-m-d'),'token'=>null]); 
            }

            return response()->json(["id_role_user"=>$user_reg->id_role_user, "id_user_reg"=>$user_reg->id_reg, "token"=>$jwt]);
        }
        
    }

    // создание уникального токена: header, Payload-тело, Signature- цифровая подпись через секретный ключ (алг. HMVAC)
    private function createToken($id_user, $id_teacher_student, $id_role_user)
    {
        $header =  json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['id_user' => $id_user,'id_teacher_student' => $id_teacher_student, 'id_role_user' => $id_role_user]);
        $base64Header = base64_encode($header);     // Encode Header to Base64 String
        $base64Payload = base64_encode($payload);  // Encode Payload to Base64 String
        // Генерация хеш-подписи на основе ключа, используя метод HMAC; 
        // microtime(true) - метка времени (текущее время в секундах, прошедших с начала эпохи Unix с точностью до микросекунд)
        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, microtime(true), true);
        $base64Signature = base64_encode($signature);
        $jwt = $base64Header . "." . $base64Payload . "." . $base64Signature; // Create JWT
        return $jwt;
    }

    // декодировка токена
    public static function decodeToken($jwt)
    {
        $array_tokenparts = array_combine(['header','payload','signature'], explode('.', $jwt));
        return json_decode(base64_decode($array_tokenparts['payload']));
    }
}
