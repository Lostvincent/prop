<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Prop extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $guarded = [];

    /**
     * Auditable events.
     *
     * @var array
     */
    protected $auditableEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function aliases()
    {
        return $this->hasMany('App\Models\Alias');
    }

    public function getImageAttribute($image)
    {
        return config('filesystems.disks.public.url').'/'.($image == '' ? 'default.jpg' : $image);
    }
}
