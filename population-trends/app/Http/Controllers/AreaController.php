<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\BasePopulation;

class AreaController extends Controller
{
    public function areaPop(){

        $areaPop = BasePopulation::select([
            'b.id',
            'b.CHIIKINAME',
            'b.POPLATION',
            'a.IDO',
            'a.KEIDO',
        ])
        ->from('base_population as b')
        ->join('area as a',function($join){
            $join->on('b.JUSHOCD', '=', 'a.JUSHOCD')
                 ->on('b.CHIIKIKBN', '=', 'a.CHIIKIKBN');
        })
        ->get();

        return $areaPop;
    }
}
