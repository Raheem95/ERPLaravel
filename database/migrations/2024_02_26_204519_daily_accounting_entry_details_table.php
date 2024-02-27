<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DailyAccountingEntryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_accounting_entry_details', function (Blueprint $table) {
            $table->increments("RestrictionTransactionID");
            $table->integer("RestrictionID");
            $table->integer("AccountID");
            $table->decimal('TransactionAmount', 10, 2)->default(0.00);
            $table->integer("TransactionType");
            $table->decimal('CurrencyValue', 10, 2)->default(0.00);
            $table->string("TransactionDetails");
            $table->decimal('CurrentBalance', 10, 2)->default(0.00);
            $table->integer('AddedBy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_accounting_entry_details');
    }
}
