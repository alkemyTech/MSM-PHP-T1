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
        // funcion para crear las tablas en la base de datos 
        Schema::create('fixed_terms', function (Blueprint $table) {
            $table->id(); 
            $table->double('amount'); 
           // $table->foreign('account_id')->references('id')->on('accounts'); //vinculacion con la tabla account
            $table->double('interest');
            $table->double('total');
            $table->integer('duration');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
        

    
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_terms'); //funcion para eliminar la tabla fixed_terms si es necesario
    }
};
