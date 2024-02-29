<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BasePopulation;
use App\Models\Area;

class PopulationController extends Controller
{
    public function year() {
        
        $year = BasePopulation::select('YEAR')
            ->distinct('YEAR')
            ->pluck('YEAR');

        return $year;

    }


    public function get5SAI(){

        $get5SAI = BasePopulation::select(['5SAI'])
            ->distinct('5SAI')
            ->selectRaw(
                "(CASE 5SAI 
                WHEN '00' THEN '0歳以上5歳未満' 
                WHEN '05' THEN '5歳以上10歳未満'
                WHEN '10' THEN '10歳以上15歳未満'
                WHEN '15' THEN '15歳以上20歳未満'
                WHEN '20' THEN '20歳以上25歳未満'
                WHEN '25' THEN '25歳以上30歳未満'
                WHEN '30' THEN '30歳以上35歳未満'
                WHEN '35' THEN '35歳以上40歳未満'
                WHEN '40' THEN '40歳以上45歳未満'
                WHEN '45' THEN '45歳以上50歳未満'
                WHEN '50' THEN '50歳以上55歳未満'
                WHEN '55' THEN '55歳以上60歳未満'
                WHEN '60' THEN '60歳以上65歳未満'
                WHEN '65' THEN '65歳以上70歳未満'
                WHEN '70' THEN '70歳以上75歳未満'
                WHEN '75' THEN '75歳以上80歳未満'
                WHEN '80' THEN '80歳以上85歳未満'
                WHEN '85' THEN '85歳以上90歳未満'
                WHEN '90' THEN '90歳以上95歳未満'
                WHEN '95' THEN '95歳以上' END) 
                AS 5SAI_NAME")
            ->get();

        return $get5SAI;
        
    }

    public function get3SEDAI(){

        $get3SEDAI = BasePopulation::select(['3SEDAI'])
            ->distinct('3SEDAI')
            ->selectRaw(
                "(CASE 3SEDAI 
                WHEN 1 THEN '年少人口' 
                WHEN 2 THEN '生産年齢人口' 
                WHEN 3 THEN '老年人口' END) 
                AS 3SEDAI_NAME")
            ->get();
    
        return $get3SEDAI;

    }

    public function getChiikiName(Request $request){

        $word = $request->chiiki;

        $getChiikiName = Area::select(['CHIIKINAME'])
            ->distinct('CHIIKINAME');

        if($word != null){
            $getChiikiName = $getChiikiName->where("CHIIKINAME", "LIKE", "%$word%");
        }

        $getChiikiName = $getChiikiName->get();
        
        return $getChiikiName;
    }

    // public function getSearchBox(Request $request){

    //     $word = $request->searchBox;

    //     $getSearchBox = Area::select(['CHIIKINAME'])
    //         ->distinct('CHIIKINAME');
        
    //     if($word != null){
    //         $getSearchBox = $getSearchBox->where("CHIIKINAME", "LIKE", "%$word%");
    //     }

    //     $getSearchBox = $getSearchBox->get();

    //     return $getSearchBox;
    // }
}
