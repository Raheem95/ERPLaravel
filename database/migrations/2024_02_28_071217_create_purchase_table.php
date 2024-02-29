<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->increments('PurchaseID');
            $table->string('PurchaseNumber');
            $table->string('SupplierID');
            $table->string('SupplierName');
            $table->integer('AccountID');
            $table->decimal('TotalPurchase', 10, 2)->default(0.00);
            $table->decimal('PaidAmount', 10, 2)->default(0.00);
            $table->integer('PurchaseStatus');
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
        Schema::dropIfExists('purchase');
    }
}
