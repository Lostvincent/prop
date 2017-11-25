<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public $timestamps = false;

    protected $guarded = [];
    protected $primaryKey = 'subject_id';

    public function prop()
    {
        return $this->belongsTo('App\Models\Prop');
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }

    public function character()
    {
        return $this->belongsTo('App\Models\Character');
    }
}
