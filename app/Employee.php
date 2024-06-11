<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $primaryKey = 'EmployeeID';

    protected $fillable = [
        'EmployeeImage',
        'EmployeeName',
        'EmployeePhone',
        'EmployeeAddress',
        'EmployeeSalary',
        'HireDate',
        'Suspended'
    ];
    public function Salaries()
    {
        return $this->hasMany(Salary::class, 'EmployeeID');
    }
    public function Loans()
    {
        return $this->hasMany(Loan::class, 'EmployeeID');
    }
}
