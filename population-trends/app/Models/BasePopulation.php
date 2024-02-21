<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Area;
use Illuminate\Support\Facades\DB;

class BasePopulation extends Model
{
    use HasFactory;

    protected $table = 'base_population';

    // 登録を許可しないカラム
    protected $guarded = [];

    public function area():BelongsTo
    {
        return $this->belongsTo(Area::class,'JUSHOCD','JUSHOCD')
            ->belongsTo(Area::class,'CHIIKIKBN','CHIIKIKBN');
    }

    // public function gettest($query)
    // {
    //     return $query -> get([
    //         '3SEDAI',DB::raw("(case 3SEDAI when 1 then '年小' when 2 then '生産年齢' when 3 then '老年') as 3SEDAI")
    //     ]);
    // }

}
