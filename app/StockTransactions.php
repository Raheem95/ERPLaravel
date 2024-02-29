<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransactions extends Model
{
    protected $table = 'stock_transaction';

    protected $primaryKey = 'TransactionID';

    protected $fillable = [
        'StockID',
        'ItemID',
        'ItemQTY',
        'TransactionDetails',
        'AddedBy',
    ];
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'StockID', 'StockID');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
}
