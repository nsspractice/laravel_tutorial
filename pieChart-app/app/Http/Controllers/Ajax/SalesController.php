<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;

class SalesController extends Controller
{
    public function index(Request $request) {

        return Sale::select('company_name', 'amount') //Saleモデルからcompany_name列,amount列を取得
            ->where('year', $request->year) //year列がリクエストの年と一致する条件を追加
            ->get(); //クエリを実行して結果を取得

    }

    public function lines() {

        return Sale::select('company_name','amount','year')
            ->get();

    }

    public function years() { 

        return Sale::select('year') //Saleモデルからyear列を取得
            ->distinct()
            // ->groupBy('year') //year列でグループ化
            ->pluck('year'); //year列の値を抽出して配列として返す
    }
}
    