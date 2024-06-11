<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->increments('SalaryID'); // Primary key
            $table->integer('EmployeeID'); // Foreign key
            $table->integer('MonthID'); // Foreign key or reference to a month table
            $table->decimal('SalaryAmount', 10, 2); // Total salary amount
            $table->decimal('PaidAmount', 10, 2); // Amount paid so far
            $table->timestamps(); // Created at and Updated at
        });
    }

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}
