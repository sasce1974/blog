<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * relationship, user has one role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(){
        return $this->belongsTo(Role::class);
    }


    /**
     * relationship, user has many posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(){
        return $this->hasMany(Post::class);
    }

    /**
     * relationship, user has many comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     * Check if user is Admin
     *
     * @return bool
     */
    public function isAdmin(){
        return $this->role()->exists() && $this->role->name == 'Admin';
    }


    /**
     * relationship, user has one photo
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function photo(){
        return $this->morphOne(Photo::class, 'photoable')
            ->where('approved', true);
    }

    /**
     * get user photo from gravatar if being placed by user
     *
     * @param null $size
     * @return string
     */
    public function avatar($size = null){

        if($size === null) $size = 32;

        return "https://www.gravatar.com/avatar/" . md5( strtolower( $this->email ) ) . "?s=" . $size;
    }

    /**
     * Get user photo if uploaded or gravatar
     *
     * @param null $size
     * @return string
     */
    public function image($size = null){

        return $this->photo ? '/storage/' . $this->photo->path : $this->avatar($size);
    }

    /**
     * accessor for image alt value or give default one
     *
     * @return string
     */
    public function getImageAltAttribute(){
        return $this->photo ? $this->photo->alt : "User photo";
    }
}
