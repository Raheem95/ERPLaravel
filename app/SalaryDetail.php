<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    protected $primaryKey = 'SalaryDetailsID';
    protected $fillable = ['SalaryID', 'Amount', 'Comment', 'RestrictionID'];

    public function Salary()
    {
        return $this->belongsTo(Salary::class, 'SalaryID');
    }
}
