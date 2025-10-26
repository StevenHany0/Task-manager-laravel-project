<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    protected function tasks(){
        return $this->belongsToMany(Task::class);
    }
}
