<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditorsDebtorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditors_debtors', function (Blueprint $table) {
            $table->increments('OprationID'); // Primary key
            $table->decimal('Amount', 15, 2); // Amount with 2 decimal places
            $table->text('OprationDetails'); // Details of the operation
            $table->string('OprationType'); // Type of operation
            $table->integer('RestrictionID'); // Foreign key to another table (assuming Restriction)
            $table->integer('AccountID'); // Foreign key to the Account table
            $table->integer('PaymentAccountID'); // Foreign key to the Account table
            $table->integer('AddedBy'); // User ID who added the record
            $table->timestamps(); // Created at and updated at timestamps

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditors_debtors');
    }
}
