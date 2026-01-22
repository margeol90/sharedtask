<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShoppingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_id',
        'created_by'
    ];
    
    protected $appends = [
        'user_name'
    ];

    protected $hidden = [
        'creator',
        'created_by',
        'created_at',
        'updated_at'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function items()
    {
        return $this->hasMany(ShoppingItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUserNameAttribute()
    {
        return $this->creator?->name;
    }
}
