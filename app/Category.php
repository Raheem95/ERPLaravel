<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'categories';

    public $primaryKey = 'CategoryID';

    protected $fillable = ['CategoryName', 'AddedBy', 'Deleted'];

    // Define relationships or custom methods here

    public function items()
    {
        return $this->hasMany(Item::class, 'CategoryID');
    }
}
