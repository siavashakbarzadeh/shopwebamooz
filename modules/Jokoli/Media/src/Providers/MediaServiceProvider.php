<?php

namespace Jokoli\Media\Providers;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadMigrations();
    }

    public function boot()
    {
        $this->_merge_config();
        config()->set('filesystems.disks.private',[
            'driver' => 'local',
            'root' => storage_path('app/private'),
        ]);
    }

    private function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
    }

    public function _merge_config()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/media.php', 'media');
    }
}
