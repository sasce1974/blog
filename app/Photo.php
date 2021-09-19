<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['path', 'alt', 'approved'];

    public function photoable(){
        return $this->morphTo();
    }
}
