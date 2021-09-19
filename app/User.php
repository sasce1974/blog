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

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function isAdmin(){
        return $this->role()->exists() && $this->role->name == 'Admin';
    }

    public function photo(){
        return $this->morphOne(Photo::class, 'photoable');
    }

    public function avatar($size = null){
        $email = $this->email;
        $default = "https://www.somewhere.com/homestar.jpg";

        if($size === null) $size = 32;

        //return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;

        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;

    }

    public function image($size = null){

        return $this->photo()->first() ? '/storage/' . $this->photo->first()->path : $this->avatar($size);
    }

    public function getImageAltAttribute(){
        return $this->photo()->first() ? $this->photo()->first()->alt : "User photo";
    }
}
