<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function entries()
    {
        return $this->hasMany(DailyAccountingEntry::class, 'AddedBy', 'id');
    }
    public function purchase()
    {
        return $this->hasMany(DailyAccountingEntry::class, 'AddedBy', 'id');
    }
    public function account()
    {
        return $this->hasMany(Account::class, 'AddedBy', 'id');
    }
    public function category()
    {
        return $this->hasMany(Category::class, 'AddedBy', 'id');
    }
    public function currency()
    {
        return $this->hasMany(Currency::class, 'AddedBy', 'id');
    }
    public function customer()
    {
        return $this->hasMany(Customer::class, 'AddedBy', 'id');
    }
    public function supplier()
    {
        return $this->hasMany(Supplier::class, 'AddedBy', 'id');
    }
    public function item()
    {
        return $this->hasMany(Item::class, 'AddedBy', 'id');
    }
    public function purchase_payment()
    {
        return $this->hasMany(PurchasePayment::class, 'AddedBy', 'id');
    }
}
