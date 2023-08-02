<?php

namespace App\Console;

use App\Console\Commands\ProcessJobResult;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ProcessJobResult::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(ProcessJobResult::class)->everyThirtyMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
