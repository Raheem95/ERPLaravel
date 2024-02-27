<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyAccountingEntryDetails extends Model
{
    protected $table = 'daily_accounting_entry_details';

    protected $primaryKey = 'RestrictionTransactionID';

    protected $fillable = [
        'RestrictionID',
        'AccountID',
        'TransactionAmount',
        'TransactionType',
        'CurrencyValue',
        'TransactionDetails',
        'CurrentBalance',
        'AddedBy'
    ];
    public function entry()
    {
        return $this->belongsTo(DailyAccountingEntry::class, 'RestrictionID', 'RestrictionID');
    }
    public function account()
    {
        return $this->belongsTo(Account::class, 'AccountID');
    }
}
