<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    public $primaryKey = 'AccountID';
    protected $fillable = [
        'AccountNumber',
        'AccountName',
        'AccountType',
        'CurrencyID',
        'Balance',
        'AddedBy'
    ];
    public function dailyAccountingEntryDetails()
    {
        return $this->hasMany(DailyAccountingEntryDetails::class, 'AccountID');
    }


}
