<?php

namespace App\Console\Commands\Generator;

use Illuminate\Console\Command;

class GenerateMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成迁移文件';

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
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
