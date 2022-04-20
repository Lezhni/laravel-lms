<?php

namespace App\Jobs\Auth;

use App\Mail\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 *
 */
class SendPasswordResetLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected string $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token = Str::random(60);

        DB::table('password_resets')
            ->where('email', $this->email)
            ->delete();

        DB::table('password_resets')->insert([
            'email' => $this->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $mail = new PasswordReset($token);
        Mail::to($this->email)->send($mail);
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts(): int
    {
        return 1;
    }
}
