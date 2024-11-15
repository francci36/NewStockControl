<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_columns_to_commandes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCommandesTable extends Migration
{
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'quantity')) {
                $table->integer('quantity')->after('product');
            }
            if (!Schema::hasColumn('commandes', 'status')) {
                $table->string('status')->after('quantity');
            }
            if (!Schema::hasColumn('commandes', 'date')) {
                $table->date('date')->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (Schema::hasColumn('commandes', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('commandes', 'date')) {
                $table->dropColumn('date');
            }
        });
    }
}
