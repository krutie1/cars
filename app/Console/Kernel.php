<?php

namespace App\Console;

use App\Models\Visit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
//        $schedule->command('')->hourly();
        $schedule->call(function () {
            $visits = Visit::query()->with('client')->whereNull('payment_date')->get();

            DB::transaction(function () use ($visits) {
                $visits->each(function ($visit) {
                    $client = $visit->client;
                    $client->decrement('visits_count');

                    $visit->delete();
                });
            });

            Log::error("Schedule run");
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
