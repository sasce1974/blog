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


    public function comments(){
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id');
    }

    public function allComments(){
        return $this->morphMany(Comment::class, 'commentable');
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

    public function setContentAttribute($content){
        $allowed_tags = ["<h1>", "<h2>", "<p>", "<b>", "<h3>", "<h4>", "<h5>", "<h6>", "<ul>", "<ol>", "<li>"];
        $this->attributes['content'] = strip_tags($content, $allowed_tags);
    }

    public function getContentAttribute($content){
        return nl2br($content);
    }
}
