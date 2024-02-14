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
        Schema::create('area', function (Blueprint $table) {
            $table->integer("CHIIKIKBN");
            $table->string("JUSHOCD");
            $table->string("CHIIKINAME");
            $table->string("JUSHO");
            $table->float("IDO",8,6); //合計8桁、小数6桁を表示
            $table->float("KEIDO",9,6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area');
    }
};
