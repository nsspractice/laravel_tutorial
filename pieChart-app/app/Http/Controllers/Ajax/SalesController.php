<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;

class SalesController extends Controller
{
    public function index(Request $request) {

        return Sale::select('company_name', 'amount') 
            ->where('year', $request->year)
            ->get();

    }

    public function years() { 

        return Sale::select('year')
            ->groupBy('year')
            ->pluck('year');
    }
}
    