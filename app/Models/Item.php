<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'code',
        'price',
        'category_id',
        'condition',
        'status',
    ];


    public function category(){
        $this->belongsTo(Category::class);
    }

    public function borrowing(){
        return $this->hasMany(Borrowing::class);
    }
}
