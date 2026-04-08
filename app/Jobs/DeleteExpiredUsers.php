<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteExpiredUsers implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $cutoff = Carbon::now()->subMonths(6);

        $deleted = User::query()
            ->where('is_admin', false)
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '<=', $cutoff)
            ->delete();

        Log::info("DeleteExpiredUsers: {$deleted} usuário(s) removido(s) com expiração anterior a {$cutoff->toDateString()}.");
    }
}
