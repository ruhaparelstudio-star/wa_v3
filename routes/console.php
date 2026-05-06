<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test {--filter=} {--testsuite=}', function (): int {
    $command = [base_path('vendor/bin/phpunit')];

    if ($this->option('filter')) {
        $command[] = '--filter';
        $command[] = $this->option('filter');
    }

    if ($this->option('testsuite')) {
        $command[] = '--testsuite';
        $command[] = $this->option('testsuite');
    }

    $process = new Process(
        $command,
        base_path(),
        ['APP_ENV' => 'testing'],
    );

    $process->setTimeout(null);

    return $process->run(function (string $type, string $buffer): void {
        $this->output->write($buffer);
    });
})->purpose('Run the application test suite');
