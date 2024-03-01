<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockItems extends Model
{
    protected $table = 'stock_items';

    protected $primaryKey = 'StockItemID';

    protected $fillable = [
        'StockID',
        'ItemID',
        'ItemQTY',
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
