<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Alias extends Model implements AuditableContract
{
    use Auditable;

    public $timestamps = false;

    protected $guarded = [];

    public function prop()
    {
        return $this->belongsTo('App\Models\Prop');
    }
}
