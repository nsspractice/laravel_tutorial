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
            'b.5SAI',
            'b.3SEDAI',
            'b.YEAR',
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

    public function yearData(Request $request){

        $yearData = BasePopulation::select([
            'ba.JUSHOCD',
            'ba.CHIIKINAME',
            'ar.IDO',
            'ar.KEIDO',
        ])
        ->selectRaw('SUM(ba.POPLATION) as population')
        ->from('area as ar')
        ->leftjoin('base_population as ba',function($join){
            $join->on('ba.JUSHOCD', '=', 'ar.JUSHOCD')
                 ->on('ba.CHIIKIKBN', '=', 'ar.CHIIKIKBN');
        })
        ->where('ba.YEAR', '=', $request->year)
        ->orWhere(function($query) {
            $query->wherein('ba.5SAI', ['00'])
                  ->orWherein('ba.3SEDAI', []);
        })        
        ->groupBy('ba.JUSHOCD','ba.CHIIKINAME','ar.IDO','ar.KEIDO')
        ->get();

        return $yearData;
        
    }
}
