<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->increments('StockItemID');
            $table->integer('StockID');
            $table->integer('ItemID');
            $table->integer('ItemQTY');
            $table->unsignedInteger('AddedBy');
            $table->timestamps();

            // Define foreign key constraint for AddedBy
            $table->foreign('StockID')->references('StockID')->on('stock');
            $table->foreign('ItemID')->references('ItemID')->on('items');
            $table->foreign('AddedBy')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_items');
    }
}
