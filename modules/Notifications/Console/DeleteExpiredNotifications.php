<?php

namespace Modules\Notifications\Console;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;

class DeleteExpiredNotifications extends Command
{
    protected const EXPIRED_AFTER_MONTHS = 2;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an old expired notifications.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $expirationDate = Carbon::now()->subMonths(self::EXPIRED_AFTER_MONTHS);

        DatabaseNotification::query()
            ->where('created_at', '<', $expirationDate)
            ->delete();

        return Command::SUCCESS;
    }
}
