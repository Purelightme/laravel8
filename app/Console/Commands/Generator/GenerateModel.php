<?php

namespace App\Console\Commands\Generator;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:model {ModelName} {--m|migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成模型文件';

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
        $arguments = $this->arguments();
        $template = file_get_contents(resource_path('stubs/model.stub'));
        $str = str_replace('{NAMESPACE}','App\Models\\'.$arguments['ModelName'],$template);
        $str = str_replace('{ModelName}',$arguments['ModelName'],$str);
        file_put_contents(app_path('Models/'.$arguments['ModelName'].'.php'),$str);

        $options = $this->options();
        if (isset($options['migration'])){
            $template = file_get_contents(resource_path('stubs/create_table.stub'));
            $str = str_replace('{ModelName}',$arguments['ModelName'],$template);
            $tableName = Str::pluralStudly(Str::snake($arguments['ModelName']));
            $str = str_replace('{TableName}',$tableName,$str);
            file_put_contents(database_path('migrations/'.now()->format('Y_m_d_His').'_create_'.$tableName.'_table.php'),$str);
        }
    }
}
