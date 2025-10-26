<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable=['title','description','priority','user_id'];
    protected function user(){
        return $this->belongsTo(User::class);
    }
    public function categories(){
        return $this->belongsToMany(Category::class);
    }
    public function favoriteByUser(){
        return $this->belongsToMany(User::class, 'favorites');
    }
}
