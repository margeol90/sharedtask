<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function shoppingLists()
    {
        return $this->hasMany(ShoppingList::class);
    }

}
