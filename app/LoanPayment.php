<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $table = 'loan_payments';
    protected $primaryKey = 'PaymentID';

    protected $fillable = [
        'LoanID',
        'Amount',
        'Comment',
        'RestrictionID',
        'Deletable',
    ];

    public function Loan()
    {
        return $this->belongsTo(Loan::class, 'LoanID');
    }

    public function Restriction()
    {
        return $this->belongsTo(DailyAccountingEntry::class, 'RestrictionID');
    }
}
