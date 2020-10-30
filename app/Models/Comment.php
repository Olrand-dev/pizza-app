<?php

namespace App\Models;

class Comment extends Model
{
    public function orders()
    {
        return $this->morphedByMany('App\Models\Order', 'commentable');
    }


    public function pizzas()
    {
        return $this->morphedByMany('App\Models\Pizza', 'commentable');
    }


    public function stocks()
    {
        return $this->morphedByMany('App\Models\Stock', 'commentable');
    }
}
