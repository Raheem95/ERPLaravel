<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('CurrencyID');
            $table->string('CurrencyName');
            $table->unsignedInteger('AddedBy');
            $table->timestamps();

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
        Schema::dropIfExists('currencies');

    }
}
