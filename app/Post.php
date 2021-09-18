<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=['title', 'content', 'slug', 'viewed', 'approved'];

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function categories(){
        return $this->belongsToMany(Category::class)->withTimestamps();
    }


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function isApproved(){
        return $this->approved === 1;
    }
}
