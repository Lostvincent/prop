<?php
namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string',
            'password' => 'required|min:6',
        ]);

        $token = app('auth')->attempt(request(['name', 'password']));

        if (!$token) {
            throw new ValidationHttpException(['password' => '账号或者密码错误']);
        }

        $user = User::where('name', $request->input('name'))->firstOrFail();

        $data = [
            'token'      => $token,
            'expired_at' => Carbon::now()->addMinutes(config('jwt.ttl'))->format('Y-m-d H:i:s'),
            'user'       => $user->toArray()
        ];
        
        return $this->response->array($data);
    }

    public function logout()
    {
        JWTAuth::parseToken()->invalidate();

        return $this->response->noContent();
    }
}
