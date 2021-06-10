<?php

namespace App\Providers;

use App\Models\Common\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $data = [];

        if(Schema::hasTable('settings')) {
            $settings = Setting::query()->get(['key', 'value', 'serialized']);
            if (!empty($settings)) {
                foreach ($settings as $setting) {
                    $key = $setting['key'];
                    $value = $setting['value'];
                    $data[$key] = $setting['serialized'] ? json_decode($value, 1) : $value;
                }
            }
        }

        config([
            'settings' => $data,
        ]);
    }
}
