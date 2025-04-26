<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'deleted_at',
    ];


    public function item(){
       return $this->hasMany(Item::class);
    }
}
