<?php

declare(strict_types=1);

namespace App;

use Zana\Cache\CacheServiceProvider;
use Zana\Console\ConsoleServiceProvider;
use Zana\Core\Application;
use Zana\Core\ApplicationBuilder;
use Zana\Http\HttpServiceProvider;
use Zana\Logging\LoggingServiceProvider;
use Zana\Routing\RoutingServiceProvider;
use Zana\Security\SecurityServiceProvider;
use Zana\Translation\TranslationServiceProvider;
use Zana\View\ViewServiceProvider;

/**
 * The application: the framework's providers here, the features in the
 * modules listed in config/modules.php. Logging comes first, so errors are
 * written to var/log/app.log (config/logging.php); the console provider brings
 * the deployment commands, release:build included.
 */
final class Bootstrap
{
    public static function builder(string $basePath): ApplicationBuilder
    {
        return Application::configure($basePath)
            ->withProviders(
                LoggingServiceProvider::class,
                HttpServiceProvider::class,
                RoutingServiceProvider::class,
                CacheServiceProvider::class,
                TranslationServiceProvider::class,
                SecurityServiceProvider::class,
                ViewServiceProvider::class,
                ConsoleServiceProvider::class,
            );
    }
}
