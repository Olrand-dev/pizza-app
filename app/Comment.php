<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function orders()
    {
        return $this->morphedByMany('App\Order', 'commentable');
    }


    public function pizzas()
    {
        return $this->morphedByMany('App\Pizza', 'commentable');
    }


    public function stocks()
    {
        return $this->morphedByMany('App\Stock', 'commentable');
    }
}
