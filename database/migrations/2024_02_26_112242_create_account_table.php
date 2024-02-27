<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('AccountID');
            $table->string('AccountNumber');
            $table->string('AccountName');
            $table->integer('AccountType');
            $table->unsignedInteger('CurrencyID');
            $table->decimal('Balance', 10, 2)->default(0.00);
            $table->unsignedInteger('AddedBy');
            $table->timestamps();

            // Define foreign key constraint for CurrencyID
            $table->foreign('CurrencyID')->references('CurrencyID')->on('currencies');

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
        Schema::dropIfExists('accounts');
    }
}
