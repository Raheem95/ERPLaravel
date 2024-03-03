<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransfare extends Model
{
    protected $table = 'stock_transfare';

    protected $primaryKey = 'TransfareID';

    protected $fillable = [
        'FromStockID',
        'ToStockID',
        'Comment',
        'AddedBy',
    ];
    
    public function transfare_details()
    {
        return $this->hasMany(StockTransfareDetails::class, 'TransfareID', 'TransfareID');
    }
    public function from_stock()
    {
        return $this->belongsTo(Stock::class, 'FromStockID', 'StockID');
    }
    public function to_stock()
    {
        return $this->belongsTo(Stock::class, 'ToStockID', 'StockID');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
}
