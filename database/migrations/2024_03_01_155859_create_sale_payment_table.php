<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_payment', function (Blueprint $table) {
            $table->increments('PaymentID');
            $table->integer('SaleID');
            $table->decimal('PaidAmount', 10, 2)->default(0.00);
            $table->integer('RestrictionID');
            $table->unsignedInteger('AddedBy');
            $table->timestamps();

            // Define foreign key constraint for CurrencyID
            $table->foreign('SaleID')->references('SaleID')->on('sale');
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
        Schema::dropIfExists('sale_payment');
    }
}
