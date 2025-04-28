<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returning extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'status_return',
    ];
}
