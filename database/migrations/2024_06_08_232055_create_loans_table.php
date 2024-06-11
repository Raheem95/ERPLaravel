<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('LoanID'); // Primary key
            $table->integer('EmployeeID'); // Foreign key to Employees table
            $table->decimal('LoanAmount', 15, 2); // Loan amount
            $table->decimal('PaidAmount', 15, 2)->default(0); // Loan amount
            $table->text('LoanDetails')->nullable(); // Details of the loan
            $table->integer('RestrictionID'); // Foreign key or another identifier
            $table->integer('LoanAccountID'); // Foreign key or another identifier
            $table->integer('PaymentAccountID'); // Foreign key or another identifier
            $table->integer('AddedBy'); // Foreign key or another identifier
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
