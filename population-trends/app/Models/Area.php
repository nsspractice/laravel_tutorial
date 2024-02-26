<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BasePopulation;

class Area extends Model
{
    use HasFactory;

    protected $table = 'area_code';

    // 登録を許可しないカラム
    protected $guarded = [];

    // 地域テーブルは人口テーブルの複数の情報を持つ
    public function basePopulation():HasMany
    {
        // return $this->hasMany(BasePopulation::class);
        return $this->hasMany(BasePopulation::class, 'JUSHOCD', 'JUSHOCD')
            ->hasMany(BasePopulation::class,'CHIIKIKBN','CHIIKIKBN');
    }

}
