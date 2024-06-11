<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditorsDebtor extends Model
{
    protected $table = 'creditors_debtors';

    // Specify the primary key (if different from the default 'id')
    protected $primaryKey = 'OprationID';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'Amount',
        'OprationDetails',
        'OprationType',
        'RestrictionID',
        'AccountID',
        'PaymentAccountID',
        'AddedBy',
    ];

    // Define relationships (if any)

    // Assuming there are related models, add the relationships here
    public function Restriction()
    {
        return $this->belongsTo(DailyAccountingEntry::class, 'RestrictionID');
    }

    public function Account()
    {
        return $this->belongsTo(Account::class, 'AccountID');
    }
    public function PaymentAccount()
    {
        return $this->belongsTo(Account::class, 'PaymentAccountID','AccountID');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
}
