<?php



// database/migrations/xxxx_xx_xx_xxxxxx_remove_duplicate_status_columns_from_commandes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDuplicateStatusColumnsFromCommandesTable extends Migration
{
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('statut')->after('date');
        });
    }
}
