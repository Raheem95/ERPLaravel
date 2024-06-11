<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('EmployeeID');
            $table->string('EmployeeImage'); // Storing the path to the image
            $table->string('EmployeeName');
            $table->string('EmployeePhone');
            $table->string('EmployeeAddress');
            $table->decimal('EmployeeSalary', 10, 2); // Salary with precision
            $table->date('HireDate');
            $table->boolean('Suspended')->default(false); // Default to not suspended
            $table->timestamps(); // This will create created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
