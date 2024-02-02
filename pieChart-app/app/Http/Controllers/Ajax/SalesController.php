<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;

class SalesController extends Controller
{
    public function index(Request $request) {

        return Sale::select('company_name','amount') //company_name, amountの2列をSalesテーブルから選択
            ->where('year',$request->year) //yearと一致するリクエストパラメータyearを取得
            ->get(); //クエリを実行して、結果をコレクションとして取得

    }

    public function years() {
        
        return Sale::select('year') //year列の選択
            ->groupBy('year') //指定した列の値でグループ化
            ->pluck('year'); //pluckは指定した列の値を配列で取得することができる

    }
}
