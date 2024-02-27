<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedToDailyAccountingEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_accounting_entry', function (Blueprint $table) {
            $table->integer('Deleted')->default(0);
            $table->integer('Deletable')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_accounting_entry', function (Blueprint $table) {
            $table->dropColumn('Deleted');
            $table->dropColumn('Deletable');
        });
    }
}
