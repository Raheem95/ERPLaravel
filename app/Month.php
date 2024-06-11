<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'monthes';
    protected $primaryKey = 'MonthID';

    protected $fillable = [
        'MonthName'
    ];


    public function Salaries()
    {
        return $this->hasMany(Salary::class, 'MonthID');
    }
}
