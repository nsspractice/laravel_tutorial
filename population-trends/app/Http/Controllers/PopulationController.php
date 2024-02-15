<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BasePopulation;

class PopulationController extends Controller
{
    public function year(Request $request) {
        
        $year = BasePopulation::select('YEAR')
            ->distinct('YEAR')
            ->pluck('YEAR');

        return $year;

    }
}
