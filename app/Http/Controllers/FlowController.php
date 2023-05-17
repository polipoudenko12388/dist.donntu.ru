<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flow;
use App\Models\User;

class FlowController extends Controller
{
    // список создателей потока
    public function ListCreatorFlow(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  {  return response()->json(Flow::getListDataFlow(['teacher.id as id_teacher', 'surname', 'user.name'])); }  
    }

    public function ListFlow(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        {  
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            $array_flows = Flow::getListDataFlow(['flow.id as id_flow', 'flow.name as name_flow', 'flow.id_creator as id_creator','surname', 'user.name as name_teacher'],
            $request->input('id_creator'), $request->input('id_group'),$request->input('name_flow'));
            
            if (count($array_flows)==0) $ListFlow=null;
            else 
            {
                for ($i=0; $i<count($array_flows); $i++) 
                { 
                    $ListFlow[$i]=array("id_flow"=>$array_flows[$i]->id_flow, "name_flow"=>$array_flows[$i]->name_flow, "surname_creator"=>$array_flows[$i]->surname, "name_creator"=>$array_flows[$i]->name_teacher);
                    if ($array_flows[$i]->id_creator == $arrayinfotoken->id_teacher_student) $ListFlow[$i]["edit_flow"]=true;
                    else $ListFlow[$i]["edit_flow"]=false;

                    $array_disc_id_flow = Flow::getListDiscFlowId($ListFlow[$i]['id_flow']);
                    if ($array_disc_id_flow[0]->id_disc==null) $ListFlow[$i]["array_disc"]=null;
                    else
                    {
                        for ($j=0; $j<count($array_disc_id_flow);$j++)
                        {
                            $ListFlow[$i]["array_disc"][$j] = array("name_disc"=>$array_disc_id_flow[$j]->name_disc);
                        }
                    }
                }
            }

            return response()->json($ListFlow); 
        }      
    }

    public function ListGroupsFlow(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else 
        { 
            $data_flow = User::SeachRecordsbyWhere("flow", "flow.id=?", $request->input('id_flow'));
            $result['id_flow']=$data_flow[0]->id; $result['name_flow']=$data_flow[0]->name;
            $result['groups']=Flow::getListGroupsFlowId($request->input('id_flow'));
            return response()->json($result); 
        }      
    }

    // создание потока
    public function ResultCreateFlow(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            $arrayinfotoken=AuthorizationController::decodeToken($request->input('token'));
            // проверка, сущ. ли поток с таким же именем, как добавляемый
            $Seachflow =  User::SeachRecordsbyWhere("flow", "flow.name=?", [$request->input('name_flow')]);
            if(count($Seachflow)>0)  return response()->json(["error"=>"Поток с таким именем уже существует."]);
            else
            {
                // добавление потока в т. flow
                $id_newflow = User::getIdInsertRecord(array("name"=>$request->input('name_flow'), "id_creator"=>$arrayinfotoken->id_teacher_student), "flow");
                
                if ($request->input('groups')!=null) { $this->InsertRecordFlowGroup($id_newflow,$request->input('groups')); }
                
                return response()->json(["info"=>"Добавление потока прошло успешно."]);
            }
        }      
    }

    // удаление потока
    public function ResultDeleteFlow(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            User::DeleteRecord("flow","flow.id=?",[$request->input('id_flow')]);
            return response()->json(["info"=>"Удаление потока прошло успешно."]);  
        }
    }

    // удаление массива групп потока
    public function ResultDeleteGroupsFlow(Request $request)
    {
        // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            if ($request->input('groups')!=null)
            {
                User::DeleteRecord("flow_group","id_flow=?",[$request->input('id_flow')], 'id_group', $request->input('groups'));
                return response()->json(["info"=>"Удаление групп потока прошло успешно."]);  
            }
            else   return response()->json(["error" => "Группы не были выбраны для удаления из потока."]);
        }
    }

    // обновление названия потока
    public function ResultUpdateNameFlow(Request $request)
    {
       // проверка токена
       $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
       if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
       else  
       { 
           User::UpdateColumn("flow", ['flow.id','=',$request->input('id_flow')], ["flow.name"=>$request->input('new_name')]);
           return response()->json(["info"=>"Обновление названия потока прошло успешно."]);  
       }
    }

    public function ResultInsertNewGroupsFlow(Request $request)
    {
         // проверка токена
        $token_verification = GeneralUserController::VerifactionToken($request->input('id_user_reg'),$request->input('token'));
        if (count($token_verification)==0) return response()->json(["error" => "Вы не авторизованы. Войдите в систему."]);
        else  
        { 
            if ($request->input('groups')!=null)
            {
                $this->InsertRecordFlowGroup($request->input('id_flow'),$request->input('groups'));
                return response()->json(["info"=>"Добавление новых групп потока прошло успешно."]);
            }
            else   return response()->json(["error" => "Группы не были выбраны для добавления в поток."]);
        }
    }

    private function InsertRecordFlowGroup($id_flow, $groups)
    {
        User::InsertRecord(array_map(function ($object)  use (&$id_flow) { return array("id_flow" => $id_flow, "id_group" => $object); },  $groups), "flow_group");
    }

}