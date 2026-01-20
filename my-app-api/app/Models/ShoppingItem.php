<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingItem extends Model
{
    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class);
    }

}
