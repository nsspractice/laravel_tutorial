<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BasePopulation;

class PopulationController extends Controller
{
    public function basePopulation(){
        return BasePopulation::all();
    }
}
