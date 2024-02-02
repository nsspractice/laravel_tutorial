<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use Illuminate\Support\Arr;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            '株式会社 恵比寿',
            '株式会社 大黒天',
            '株式会社 毘沙門天',
            '株式会社 弁財天',
            '株式会社 福禄寿',
            '株式会社 寿老人',
            '株式会社 布袋',
        ];

        for($i = 0; $i < 100; $i++) {
            
            $sale = new Sale;
            $sale->company_name = Arr::random($companies);
            $sale->amount = mt_rand(1000, 10000) * 10 ;
            $sale->year = mt_rand(2018, 2020);
            $sale->save();

            //$sale = new Sale;でいいのでは？
            //randよりmt_randのほうが高速かつ生成に優れている
            //ヘルパ関数Arr::randomを使用

        }
    }
}
