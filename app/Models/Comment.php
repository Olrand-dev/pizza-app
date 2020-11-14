<?php

namespace App\Models;

class Comment extends Model
{
    public function user()
    {
        return $this->hasOne('App\Models\User');
    }


    public function commentable()
    {
        return $this->morphTo();
    }
}
