<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments("ItemID");
            $table->string('ItemPartNumber');
            $table->string('ItemName');
            $table->decimal('ItemPrice', 10, 2);
            $table->decimal('ItemSalePrice', 10, 2);
            $table->integer('ItemQty');
            $table->integer('Minimum');
            $table->integer('ItemCategory');
            $table->unsignedBigInteger('AddedBy');
            $table->integer('Deleted')->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('ItemCategory')->references('CategoryID')->on('categories');
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
        Schema::dropIfExists('items');

    }
}
