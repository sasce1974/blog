<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = ['title', 'content', 'slug', 'viewed', 'approved'];

    private $allowed_tags = ["<h1>", "<h2>", "<p>", "<b>", "<h3>", "<h4>", "<h5>", "<h6>", "<ul>", "<ol>", "<li>", ];

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function categories(){
        return $this->belongsToMany(Category::class)->withTimestamps();
    }


    /**
     * Comments that are directly related to some post
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(){
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id');
    }

    /**
     * Get all comments for the admin dashboard
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function allComments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function photo(){
        return $this->morphOne(Photo::class, 'photoable')
            ->where('approved', true);
    }

    public function image($width = null, $height = null){

        return $this->photo ? '/storage/' . $this->photo->path : $this->randomImage($width, $height);
    }

    public function getImageAltAttribute(){
        return $this->photo ? $this->photo->alt : "Post image";
    }

    private function randomImage($width, $height){
        if($width != null && $height != null){
            return "https://source.unsplash.com/random/" . $width . 'x' . $height;
        }
        return "https://source.unsplash.com/random";
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

        $this->attributes['content'] = strip_tags($content, $this->allowed_tags);
    }

    public function getContentAttribute($content){

        return nl2br(strip_tags($content, $this->allowed_tags));
    }
}
