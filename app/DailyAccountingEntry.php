<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyAccountingEntry extends Model
{
    protected $table = 'daily_accounting_entry';

    protected $primaryKey = 'RestrictionID';

    protected $fillable = [
        'RestrictionDetails',
        'AddedBy',
        'Deletable',
        'Deleted',
    ];
    public function details()
    {
        return $this->hasMany(DailyAccountingEntryDetails::class, 'RestrictionID', 'RestrictionID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }

}
