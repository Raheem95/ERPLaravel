<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salary extends Model
{
    protected $table = 'salaries';
    protected $primaryKey = 'SalaryID';
    protected $fillable = ['EmployeeID', 'MonthID', 'SalaryAmount', 'PaidAmount'];

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID');
    }
    public function SalaryDetail()
    {
        return $this->hasMany(SalaryDetail::class, 'SalaryID');
    }
    public function Month()
    {
        return $this->belongsTo(Month::class, 'MonthID');
    }
}
