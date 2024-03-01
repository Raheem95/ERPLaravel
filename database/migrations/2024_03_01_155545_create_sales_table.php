<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->increments('SaleID');
            $table->string('SaleNumber');
            $table->string('CustomerID');
            $table->string('CustomerName');
            $table->integer('AccountID');
            $table->decimal('TotalSale', 10, 2)->default(0.00);
            $table->decimal('PaidAmount', 10, 2)->default(0.00);
            $table->integer('SaleStatus');
            $table->integer('StockID');
            $table->integer('RestrictionID');
            $table->unsignedInteger('Transfer');
            $table->unsignedInteger('CurrencyID');
            $table->unsignedInteger('AddedBy');
            $table->timestamps();

            // Define foreign key constraint for CurrencyID
            $table->foreign('CurrencyID')->references('CurrencyID')->on('currencies');
            // Define foreign key constraint for CurrencyID
            $table->foreign('RestrictionID')->references('RestrictionID')->on('daily_accounting_entry');

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
        Schema::dropIfExists('sale');
    }
}
