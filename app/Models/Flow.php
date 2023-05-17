<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Flow extends Model
{
    public static $ConnectDBWebsite="mysql2";

    // данные по потокам: создатель потока/название потока
    public static function getListDataFlow($arraynamecolumnprint, $id_creator=null, $id_group=null,$name_flow=null)
    {
        $result = DB::connection(Flow::$ConnectDBWebsite)->table('flow')
        ->distinct()
        ->select($arraynamecolumnprint)
        ->leftJoin('flow_group' , 'flow_group.id_flow','flow.id')
        ->join('teacher', 'flow.id_creator','=','teacher.id')
        ->join('user', 'teacher.id_user','=','user.id')
        ->when($id_creator, function ($query, $id_creator)
        {
            $query->where('flow.id_creator', $id_creator);
        })
        ->when($id_group, function ($query, $id_group)
        {
            $query->where('flow_group.id_group', $id_group);
        })
        ->when($name_flow, function ($query, $name_flow)
        {
            $query->where('flow.name', 'like', '%'.$name_flow.'%');
        })
        ->get();
        return $result;            
    }

    public static function getListGroupsFlowId($id_flow)
    {
        $result = DB::connection(Flow::$ConnectDBWebsite)->table('flow')
        ->select('flow_group.id_group', 'group.name as name_group')
        ->leftJoin('flow_group' , 'flow_group.id_flow','flow.id')
        ->leftJoin('group' , 'flow_group.id_group','group.id')
        ->where('flow.id', $id_flow)
        ->get();

        return $result;
    }

    // список дисциплин, в который входит поток
    public static function getListDiscFlowId($id_flow)
    {
        $result = DB::connection(Flow::$ConnectDBWebsite)->table('flow')
        ->select('list_disciplines.id as id_disc', 'list_disciplines.name as name_disc')
        ->leftJoin('discipline_flow', 'flow.id','=','discipline_flow.id_flow')
        ->leftJoin('list_disciplines', 'discipline_flow.id_list_discipline','=','list_disciplines.id')
        ->where('flow.id',$id_flow)
        ->get();
        return $result;     
    }
}
