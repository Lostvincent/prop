<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $guarded = [];
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public static function resolveId()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }

    public function auditable()
    {
        return method_exists($this->morphTo(), 'isForceDeleting') ? $this->morphTo()->withTrashed() : $this->morphTo();
    }

    public function getTypeAttribute()
    {
        return strtolower(substr($this->auditable_type, 11));
    }
}