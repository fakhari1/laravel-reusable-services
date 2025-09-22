<?php

namespace Modules\Shared\Helpers;

use Illuminate\Support\Facades\Storage;

/**
 * Defined Helpers as static functions
 */
class GlobalHelpers
{
    /**
     * Return root directory of modules
     * @return string
     */
    public static function modulesPath(): string
    {
        return base_path("modules");
    }

    /**
     * Return path of a specific module
     * @param string $moduleDirectory
     * @return string
     */
    public static function modulePath(string $moduleDirectory): string
    {
        return self::modulesPath() . DIRECTORY_SEPARATOR . $moduleDirectory . DIRECTORY_SEPARATOR;
    }


    /**
     * Load all routes of each module with naming standard
     * @param string $guard
     * @return string[]
     */
    public static function loadModulesRoutes(string $guard = 'web'): array
    {
        return [
            base_path('routes') . "/{$guard}.php",
            self::modulePath('Central') . "Routes/admin_central_{$guard}_routes.php",
            self::modulePath('Dashboard') . "Routes/admin_dashboard_{$guard}_routes.php",
            self::modulePath('Dashboard') . "Routes/tenant_dashboard_{$guard}_routes.php",
            self::modulePath('Feature') . "Routes/admin_feature_{$guard}_routes.php",
            self::modulePath('File') . "Routes/admin_file_{$guard}_routes.php",
            self::modulePath('Finance') . "Routes/admin_finance_{$guard}_routes.php",
            self::modulePath('Finance') . "Routes/tenant_finance_{$guard}_routes.php",
            self::modulePath('Front') . "Routes/front_{$guard}_routes.php",
            self::modulePath('Identity') . "Routes/auth/auth_{$guard}_routes.php",
            self::modulePath('Identity') . "Routes/user/admin_user_{$guard}_routes.php",
            self::modulePath('Identity') . "Routes/user/tenant_user_{$guard}_routes.php",
            self::modulePath('Industry') . "Routes/admin_industry_{$guard}_routes.php",
            self::modulePath('Industry') . "Routes/tenant_industry_{$guard}_routes.php",
            self::modulePath('Messenger') . "Routes/admin_messenger_{$guard}_routes.php",
            self::modulePath('Messenger') . "Routes/tenant_messenger_{$guard}_routes.php",
            self::modulePath('Payment') . "Routes/admin_payment_{$guard}_routes.php",
            self::modulePath('Payment') . "Routes/tenant_payment_{$guard}_routes.php",
            self::modulePath('Product') . "Routes/admin_product_{$guard}_routes.php",
            self::modulePath('Product') . "Routes/tenant_product_{$guard}_routes.php",
            self::modulePath('Project') . "Routes/admin_project_{$guard}_routes.php",
            self::modulePath('Project') . "Routes/tenant_project_{$guard}_routes.php",
            self::modulePath('RolePermission') . "Routes/admin_permission_{$guard}_routes.php",
            self::modulePath('RolePermission') . "Routes/tenant_permission_{$guard}_routes.php",
            self::modulePath('Service') . "Routes/admin_service_{$guard}_routes.php",
            self::modulePath('Service') . "Routes/tenant_service_{$guard}_routes.php",
            self::modulePath('Tenancy') . "Routes/admin_tenant_{$guard}_routes.php",
            self::modulePath('Tenancy') . "Routes/tenant_tenant_{$guard}_routes.php",
            self::modulePath('Settings') . "Routes/admin_settings_{$guard}_routes.php",
            self::modulePath('Settings') . "Routes/tenant_settings_{$guard}_routes.php",
            self::modulePath('Report') . "Routes/admin_report_{$guard}_routes.php",
            self::modulePath('Report') . "Routes/tenant_report_{$guard}_routes.php",
        ];
    }

    /**
     * @param array $sections
     * @param string $disk
     * @return string
     */
    public static function makeDirectoryWithTheseSections(array $sections, string $disk = 'public'): string
    {
        $url = implode(DIRECTORY_SEPARATOR, $sections) . DIRECTORY_SEPARATOR;
        Storage::disk($disk)->makeDirectory($url);

        return $url;
    }

    public static function isFullSheet($orderGroup): bool
    {
        if ($orderGroup['panel_height'] == 366 && $orderGroup['panel_width'] == 183) return true;
        return false;
    }

    public static function farsiToEnglishNumbers($text) {

        $farsiDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($farsiDigits, $englishDigits, $text);
    }
}
