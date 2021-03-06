<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Relation extends Model implements AuditableContract
{
    use Auditable;

    public $timestamps = false;

    protected $guarded = [];
    protected $primaryKey = 'subject_id';

    /**
     * Auditable events.
     *
     * @var array
     */
    protected $auditableEvents = [
        'created',
        'deleted',
    ];

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
