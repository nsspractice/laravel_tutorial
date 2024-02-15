<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Area;

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

}
