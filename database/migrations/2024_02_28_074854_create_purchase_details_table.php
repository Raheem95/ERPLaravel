<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('PurchaseDetailsID');
            $table->integer('PurchaseID');
            $table->integer('ItemID');
            $table->integer('ItemQTY');
            $table->decimal('ItemPrice', 10, 2)->default(0.00);
            $table->integer('Transfare');
            $table->timestamps();

            // Define foreign key constraint for CurrencyID
            $table->foreign('ItemID')->references('ItemID')->on('items');
            // Define foreign key constraint for CurrencyID
            $table->foreign('PurchaseID')->references('PurchaseID')->on('purchase');

            // Define foreign key constraint for AddedBy
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
}
