<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShoppingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_completed'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class);
    }

}
