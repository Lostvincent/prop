<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Dingo\Api\Exception\ValidationHttpException;

trait ControllerValidate
{
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationHttpException($validator->errors());
    }
    
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        return $request->only(array_keys($rules));
    }
}
