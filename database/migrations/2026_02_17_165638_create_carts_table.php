<?php
// database/migrations/2026_02_17_xxxxxx_create_carts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable()->index();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            // Empêcher les doublons
            $table->unique(['user_id', 'product_id']);
            $table->unique(['session_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};