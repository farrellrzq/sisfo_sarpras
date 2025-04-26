<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'status_borrow',
        'borrow_date',
        'return_date',
        'deleted_at',
    ];
    public function log(){
        return $this->hasMany(Log::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }
}

