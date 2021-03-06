<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Post extends Model
{

    protected $fillable = ['title', 'content', 'slug', 'viewed', 'approved'];

    private $allowed_tags = ["<h1>", "<h2>", "<p>", "<b>", "<h3>", "<h4>", "<h5>", "<h6>", "<ul>", "<ol>", "<li>", ];

    /**
     * relationship - belongs to one, user
     *
     * @return BelongsTo
     */
    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * relationship - many to many, categories
     *
     * @return BelongsToMany
     */
    public function categories(){
        return $this->belongsToMany(Category::class)->withTimestamps();
    }


    /**
     * Comments that are directly related to some post
     *
     * @return MorphMany
     */
    public function comments(){
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id');
    }

    /**
     * Get all comments for the admin dashboard
     *
     * @return MorphMany
     */
    public function allComments(){
        return $this->morphMany(Comment::class, 'commentable');
    }


    /**
     * Post photo, approved
     *
     * @return MorphOne
     */
    public function photo(){
        return $this->morphOne(Photo::class, 'photoable')
            ->where('approved', true);
    }

    /**
     * Get post photo path or random image path
     *
     * @param null $width
     * @param null $height
     * @return string
     */
    public function image($width = null, $height = null){

        return $this->photo ? '/storage/' . $this->photo->path :
            $this->randomImage($width, $height);
    }

    /**
     * accessor for post image alt
     *
     * @return string
     */
    public function getImageAltAttribute(){
        return $this->photo ? $this->photo->alt : "Post image";
    }

    /**
     * Get random image from unsplash.com
     *
     * @param $width
     * @param $height
     * @return string
     */
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


    /**
     * Check if post is approved
     *
     * @return bool
     */
    public function isApproved(){
        return $this->approved === 1;
    }

    /**
     * Mutator - Allows only defined tags into post->content column
     *
     * @param $content
     */
    public function setContentAttribute($content){

        $this->attributes['content'] = strip_tags($content, $this->allowed_tags);
    }

    /**
     * Accessor for content column
     *
     * @param $content
     * @return string
     */
    public function getContentAttribute($content){

        return strip_tags($content, $this->allowed_tags);
    }
}
