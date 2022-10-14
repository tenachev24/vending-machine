<?php

declare(strict_types=1);

namespace App\Application\Console\Commands;

use Illuminate\Console\Command;

class CodeFixerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpcode:fix {directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PHP Coding Standards Fixer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $basePath = base_path();
        $argument = $this->argument('directory');

        $command = shell_exec("cd $basePath && tools/php-cs-fixer/vendor/bin/php-cs-fixer fix $argument");

        json_encode(json_decode($command), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
