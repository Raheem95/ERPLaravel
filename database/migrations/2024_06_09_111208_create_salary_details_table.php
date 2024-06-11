<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_details', function (Blueprint $table) {
            $table->increments('SalaryDetailsID'); // Primary key
            $table->integer('SalaryID'); // Foreign key to the salaries table
            $table->decimal('Amount', 10, 2); // Amount of the salary detail
            $table->text('Comment')->nullable(); // Comment on the salary detail
            $table->integer('RestrictionID');
            $table->integer('Type')->default(0);
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
        Schema::dropIfExists('salary_details');
    }
}
