<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{

    protected $fillable = [
        'name',
        'account_id',
        'created_by'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function items()
    {
        return $this->hasMany(ShoppingItem::class);
    }
}
