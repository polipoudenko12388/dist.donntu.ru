<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Human extends Model
{
    protected $ConnectDBUniv="mysql";
    
    // поиск человека по снилсу/инн
    public function SearchPersonbyInnSnils($inn_snils)
    {
        $result = DB::connection($this->ConnectDBUniv)->table('human')
            ->select('id')
            ->where('snils', $inn_snils) ->orWhere('inn', $inn_snils)
             ->first();
        return $result;
    }

    // получение данных конкретного human
    public function getDataHuman($id_human)
    {
        $result = DB::connection($this->ConnectDBUniv)->table('human')
        ->select('human.id','human.surname','human.name','human.patronymic','human.datebirth','human.email', 'human.phone','human.photo',
        'country.name as name_country', 'types_of_regions.name as type_regions',  'regions.name as name_regions',
        'types_of_settlements.name as type_settlements', 'names_of_settlements.name as name_settlements')
        ->join('place_of_residence', 'human.id_placeresidence','=','place_of_residence.id')
        ->leftJoin('country', 'country.id','=','place_of_residence.id_country')
        ->leftJoin('types_of_regions', 'types_of_regions.id','=','place_of_residence.id_typesregions')
        ->leftJoin('regions', 'regions.id','=','place_of_residence.id_regions')
        ->leftJoin('types_of_settlements', 'types_of_settlements.id','=','place_of_residence.id_typesettlements')
        ->leftJoin('names_of_settlements', 'names_of_settlements.id','=','place_of_residence.id_namesettlements')
        ->where('human.id',$id_human)->first();

        return $result;
    }
}
