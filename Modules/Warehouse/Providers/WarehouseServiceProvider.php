<?php

namespace Modules\Modules\Warehouse\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Central\Utils\Helpers;

class WarehouseServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadMigrationsFrom(Helpers::modulePath('Warehouse') . 'Database/Migrations');

        $this->loadRoutesFrom(Helpers::modulePath('Warehouse') . 'Routes/tenant/api_warehouse_routes.php');
    }
}
