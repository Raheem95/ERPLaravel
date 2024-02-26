<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    public $primaryKey = 'ItemID';

    protected $fillable = ['ItemID', 'ItemPartNumber', 'ItemName', 'ItemPrice', 'ItemSalePrice', 'Minimum', 'CategoryID', 'AddedBy'];
    public function categories()
    {
        return $this->belongsTo(Category::class, 'CategoryID');
    }
}
