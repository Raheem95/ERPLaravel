<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $primaryKey = 'StockID';

    protected $fillable = [
        'StockName',
        'AddedBy',
    ];
    public function stock_items()
    {
        return $this->hasMany(StockItems::class, 'StockID', 'StockID');
    }
    public function stock_transactions()
    {
        return $this->hasMany(StockTransactions::class, 'StockID', 'StockID');
    }
    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'StockID', 'StockID');
    }
    public function sale()
    {
        return $this->hasMany(Sale::class, 'StockID', 'StockID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
}
