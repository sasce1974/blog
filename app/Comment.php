<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function replies(){
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function isApproved(){
        return $this->approved === 1;
    }
}
