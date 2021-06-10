<?php

namespace App\Providers;

use App\Utils\FileUtil;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    protected $configFile = 'table_columns.json';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 注册列数据到config上下文中
        $configFilePath = config_path($this->configFile);
        $tables = [];
        if(file_exists($configFilePath)) {
            $tables = FileUtil::loadJson($configFilePath);
        }

        config([
            'table_columns' => $tables ?? [],
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
