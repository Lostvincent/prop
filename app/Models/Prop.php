<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prop extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function getImageAttribute($image)
    {
        return config('filesystems.disks.public.url').'/'.($image == '' ? 'default.jpg' : $image);
    }
}
