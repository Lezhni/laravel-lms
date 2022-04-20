<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginsRequest;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /**
     * @param \App\Http\Requests\Auth\LoginsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginsRequest $request): JsonResponse
    {
        $email = $request->post('email');
        $password = $request->post('password');
        $rememberMe = $request->post('remember_me', false);

        $loginData = compact('email', 'password');
        if (! Auth::attempt($loginData, $rememberMe)) {
            return Response::json([
                'message' => 'Неправильный логин или пароль',
            ], 401);
        }

        $user = Auth::user();
        $user->load('permissions');
        $token = $user->createToken('API Auth Token')->accessToken;

        $resource = new UserResource($user);
        $resource = $resource->additional(['data' => ['token' => $token]]);
        return $resource->response()->setStatusCode(200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        $token = Auth::user()->token;
        $token->revoke();

        return Response::json([
            'message' => 'Вы успешно вышли из системы',
        ]);
    }
}
