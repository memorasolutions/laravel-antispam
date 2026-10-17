<?php

declare(strict_types=1);

/**
 * @author MEMORA solutions, https://memora.solutions - info@memora.ca
 * @project laravel-antispam
 */

namespace Modules\AntiSpam\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Modules\AntiSpam\Http\Middleware\AntiSpam;
use Modules\AntiSpam\Http\Middleware\BlockBots;

final class AntiSpamServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerTranslations();
        $this->registerMiddleware();
    }

    public function register(): void {}

    protected function registerConfig(): void
    {
        $configPath = dirname(__DIR__, 2) . '/config/antispam.php';

        $this->publishes([
            $configPath => config_path('antispam.php'),
        ], 'antispam-config');

        $this->mergeConfigFrom($configPath, 'antispam');
    }

    protected function registerViews(): void
    {
        $viewsPath = dirname(__DIR__, 2) . '/resources/views';

        $this->publishes([
            $viewsPath => resource_path('views/vendor/antispam'),
        ], 'antispam-views');

        $this->loadViewsFrom($viewsPath, 'antispam');
    }

    protected function registerTranslations(): void
    {
        $langPath = dirname(__DIR__, 2) . '/lang';

        $this->publishes([
            $langPath => lang_path('vendor/antispam'),
        ], 'antispam-lang');

        $this->loadTranslationsFrom($langPath, 'antispam');
    }

    protected function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('antispam', AntiSpam::class);
        $router->aliasMiddleware('block-bots', BlockBots::class);
    }
}
