<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\BasePopulation;

class AreaController extends Controller
{
    public function area(){
        $area_miyaki = BasePopulation::find(1);
        dd($area_miyaki->area);
        return $area_miyaki;

    }
}
