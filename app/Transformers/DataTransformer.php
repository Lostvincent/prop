<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

class DataTransformer extends TransformerAbstract
{
    public function transform(Model $data)
    {
        return $data->toArray();
    }
}
