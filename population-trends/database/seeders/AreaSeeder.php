<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run()
    {
        $file = new \SplFileObject('database/seeders/csv/area.csv');

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
                    'CHIIKIKBN' => $line[0],
                    'JUSHOCD' => $line[1],
                    'CHIIKINAME' => $line[2],
                    'JUSHO' => $line[3],
                    'IDO' => $line[4],
                    'KEIDO' => $line[5]
                ];

                if ($counter % 1000 === 0) {
                    Area::insert($array);
                    $array = [];
                }

                $counter++;
                $file->next();
            }
            
            if (count($array)) {
                Area::insert($array);
                $array = [];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
        }
    }
}
