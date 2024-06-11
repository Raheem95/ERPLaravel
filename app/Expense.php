<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'ExpensesID';

    protected $fillable = [
        'ExpensesAccountID',
        'PaymentAccountID',
        'ExpensesDetails',
        'ExpensesAmount',
        'RestrictionID',
        'AddedBy'
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
    public function ExpensesAccount()
    {
        return $this->belongsTo(Account::class, 'ExpensesAccountID', 'AccountID');
    }
    public function PaymentAccount()
    {
        return $this->belongsTo(Account::class, 'PaymentAccountID', 'AccountID');
    }
    public function RestrictionID()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
    public function Restriction()
    {
        return $this->belongsTo(DailyAccountingEntry::class, 'RestrictionID');
    }
}
