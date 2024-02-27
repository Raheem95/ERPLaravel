<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    public $primaryKey = 'CurrencyID';

    protected $fillable = ['CurrencyName', 'AddedBy'];
}
