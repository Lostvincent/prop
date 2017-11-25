<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use App\Traits\ControllerValidate;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use Helpers, ControllerValidate;
    //
}
