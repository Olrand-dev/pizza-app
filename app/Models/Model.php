<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function getImageUrlAttribute()
    {
        return (!empty($this->image)) ?
            asset('storage/' . $this->image) :
            asset('storage/system/no_photo.png');
    }
}
