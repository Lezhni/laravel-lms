<?php

namespace App\Console\Commands\Auth;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevokePasswordTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:revoke-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revoke expired password reset\'s tokens';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $hourAgo = Carbon::now()->subHour();

        DB::table('password_resets')
            ->where('created_at', '<', $hourAgo)
            ->delete();

        $this->info('Expired tokens deleted');

        return 0;
    }
}
