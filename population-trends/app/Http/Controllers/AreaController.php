<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\BasePopulation;

class AreaController extends Controller
{
    public function mapOptions() {
        $average = Area::selectRaw('AVG(IDO) AS AvgIDO,AVG(KEIDO) AS AvgKEIDO')
        ->from('area_code')
        ->get();

        return $average;
    }

    public function popData(Request $request){

        $popData = BasePopulation::select([
            'ba.JUSHOCD',
            'ba.CHIIKINAME',
            'ar.IDO',
            'ar.KEIDO',
        ])
        ->selectRaw('SUM(ba.POPLATION) as population')
        ->from('area_code as ar')
        ->leftjoin('base_population as ba',function($join){
            $join->on('ba.JUSHOCD', '=', 'ar.JUSHOCD')
                 ->on('ba.CHIIKIKBN', '=', 'ar.CHIIKIKBN');
        })
        ->where('ba.YEAR', '=', $request->year)
        ->groupBy('ba.JUSHOCD','ba.CHIIKINAME','ar.IDO','ar.KEIDO');

        if($request->fiveage != null){
            $fiveage = explode(',', $request->fiveage);
            $popData = $popData->wherein('ba.5SAI', $fiveage);

        }elseif($request->sedai != null){
            $sedai = explode(',', $request->sedai);
            $popData = $popData->wherein('ba.3SEDAI', $sedai);
        }

        $popData = $popData->get();

        return $popData;
        
    }
}
