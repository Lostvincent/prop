<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Auth\Factory as Auth;

class RoleCheck
{
    use Helpers;

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $roles = [];
        $fields = ['admin', 'editor', 'user']; // hight to low

        foreach ($fields as $field) {
            $roles[] = $field;
            if ($role == $field && !in_array($this->auth->user()->role, $roles)) {
                return $this->response->errorForbidden('Permission denied.');
            }
        }

        return $next($request);
    }
}
