<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordReset\LinkRequest;
use App\Http\Requests\Auth\PasswordReset\ResetRequest;
use App\Jobs\Auth\SendPasswordResetLink;
use App\Mail\PasswordResetConfirmed;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 *
 */
class PasswordResetController extends Controller
{
    /**
     * @param \App\Http\Requests\Auth\PasswordReset\LinkRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLink(LinkRequest $request): JsonResponse
    {
        $email = $request->post('email');

        SendPasswordResetLink::dispatch($email);

        return new JsonResponse([
            'message' => 'Ссылка на восстановление пароля отправлена на ваш email',
        ]);
    }

    /**
     * @param \App\Http\Requests\Auth\PasswordReset\ResetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(ResetRequest $request): JsonResponse
    {
        $token = $request->post('token');

        $email = DB::table('password_resets')
            ->where('token', $token)
            ->value('email');
        if ($email == null) {
            return new JsonResponse([
                'message' => 'Токен восстановления устарел',
            ], 422);
        }

        $password = $request->post('password');
        $passwordHash = Hash::make($password);

        $user = User::where('email', $email)->first();
        $user->password = $passwordHash;
        
        try {
            $user->token()?->revoke();
            $user->saveOrFail();
        } catch (Throwable $e) {
            report($e);
            return new JsonResponse([
                'message' => 'Не удалось сменить пароль, попробуйте еще раз',
            ], 500);
        }

        DB::table('password_resets')
            ->where('token', $token)
            ->delete();

        $confirmMail = new PasswordResetConfirmed($password);
        Mail::to($email)->send($confirmMail);

        return new JsonResponse([
            'message' => 'Пароль успешно изменен',
        ]);
    }
}
