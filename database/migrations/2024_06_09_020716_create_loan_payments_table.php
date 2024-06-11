<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->increments('PaymentID');
            $table->integer('LoanID')->constrained('loans')->onDelete('cascade');
            $table->decimal('Amount', 15, 2);
            $table->text('Comment')->nullable();
            $table->integer('RestrictionID')->constrained('restrictions')->onDelete('cascade');
            $table->integer('Deletable')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_payments');
    }
}
