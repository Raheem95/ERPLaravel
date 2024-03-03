<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransfareDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfare_details', function (Blueprint $table) {
            $table->increments('TransfareDetailsID');
            $table->integer('TransfareID');
            $table->integer('ItemID');
            $table->integer('ItemQTY');
            $table->timestamps();

            // Define foreign key constraint for CurrencyID
            $table->foreign('TransfareID')->references('TransfareID')->on('stock_transfare');
            $table->foreign('ItemID')->references('ItemID')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transfare_details');
    }
}
