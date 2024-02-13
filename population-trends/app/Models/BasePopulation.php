<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasePopulation extends Model
{
    use HasFactory;

    protected $table = 'base_population';

    // 登録を許可しないカラム
    protected $guarded = [];

}
