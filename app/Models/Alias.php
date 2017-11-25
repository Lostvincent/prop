<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function prop()
    {
        return $this->belongsTo('App\Models\Prop');
    }
}
