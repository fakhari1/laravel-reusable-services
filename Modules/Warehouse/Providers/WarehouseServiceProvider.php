<?php

namespace Modules\Warehouse\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Shared\Helpers\GlobalHelpers;

class WarehouseServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadMigrationsFrom(GlobalHelpers::modulePath('Warehouse') . 'Database/Migrations');

        $this->loadRoutesFrom(GlobalHelpers::modulePath('Warehouse') . 'Routes/tenant/api_warehouse_routes.php');
    }
}
