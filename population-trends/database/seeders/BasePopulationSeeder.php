<?php

namespace Database\Seeders;

use App\Models\BasePopulation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasePopulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = new \SplFileObject('database/seeders/csv/base_population.csv');

        $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        );

        DB::beginTransaction();
        try {
            $file->rewind(); // 最初の行へ(初期化)
            $file->next(); // 1行目スキップ

            $counter = 0;
            while ($file->valid()) {
                // 現在の行をエンコードして配列で取得
                $line = mb_convert_encoding($file->current(), 'UTF-8');

                $array[] = [
                    'CHIIKIKBN' => $line[1],
                    'JUSHOCD' => $line[3],
                    'CHIIKINAME' => $line[4],
                    'JUSHO' => $line[5],
                    '5SAI' => $line[6],
                    '3SEDAI' => $line[7],
                    'YEAR' => $line[8],
                    'SEX' => $line[9],
                    'POPLATION' => $line[10]
                ];

                if ($counter % 1000 === 0) {
                    BasePopulation::insert($array);
                    $array = [];
                }

                $counter++;
                $file->next();
            }

            if (count($array)) {
                BasePopulation::insert($array);
                $array = [];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
        }
    }
}
