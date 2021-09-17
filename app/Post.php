<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=['title', 'content', 'photo', 'category_id', 'viewed'];

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }


}
