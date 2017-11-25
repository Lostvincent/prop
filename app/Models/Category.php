<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
