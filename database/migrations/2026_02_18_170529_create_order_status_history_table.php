<?php
// database/migrations/xxxx_xx_xx_create_order_status_history_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->text('comment')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            // Index pour améliorer les performances des requêtes
            $table->index(['order_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_status_history');
    }
};