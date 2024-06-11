<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loans';
    protected $primaryKey = 'LoanID';

    protected $fillable = [
        'EmployeeID',
        'LoanAmount',
        'PaidAmount',
        'LoanDetails',
        'RestrictionID',
        'LoanAccountID',
        'PaymentAccountID',
        'AddedBy'
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID');
    }
    public function LoanAccount()
    {
        return $this->belongsTo(Account::class, 'LoanAccountID', 'AccountID');
    }
    public function PaymentAccount()
    {
        return $this->belongsTo(Account::class, 'PaymentAccountID', 'AccountID');
    }
    public function Restriction()
    {
        return $this->belongsTo(DailyAccountingEntry::class, 'RestrictionID');
    }
    public function RestrictionDetails()
    {
        return $this->hasMany(DailyAccountingEntryDetails::class, 'RestrictionID');
    }
    public function Payments()
    {
        return $this->hasMany(LoanPayment::class, 'LoanID');
    }
}
