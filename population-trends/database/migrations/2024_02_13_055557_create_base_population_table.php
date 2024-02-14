<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('base_population', function (Blueprint $table) {
            $table->increments('id');
            $table->string('CHIIKIKBN');
            $table->string('JUSHOCD');
            $table->string('CHIIKINAME');
            $table->string('JUSHO');
            $table->integer('5SAI');
            $table->string('3SEDAI');
            $table->integer('YEAR' );
            $table->boolean('SEX' );
            $table->integer('POPLATION');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_population');
    }
};
