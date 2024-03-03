<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransfareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfare', function (Blueprint $table) {
            $table->increments('TransfareID');
            $table->integer('FromStockID');
            $table->integer('ToStockID');
            $table->string('Comment');
            $table->integer('Transfare')->default(0);
            $table->unsignedInteger('AddedBy');
            $table->timestamps();

            // Define foreign key constraint for CurrencyID
            $table->foreign('FromStockID')->references('StockID')->on('stock');
            $table->foreign('ToStockID')->references('StockID')->on('stock');

            // Define foreign key constraint for AddedBy
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
        Schema::dropIfExists('stock_transfare');
    }
}
