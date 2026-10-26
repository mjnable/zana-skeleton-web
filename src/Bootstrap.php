<?php

declare(strict_types=1);

namespace App;

use App\Console\ReleaseBuildCommand;
use Zana\Cache\CacheServiceProvider;
use Zana\Console\ConsoleKernel;
use Zana\Console\ConsoleServiceProvider;
use Zana\Container\Contracts\ServiceCollectionInterface;
use Zana\Core\Application;
use Zana\Core\ApplicationBuilder;
use Zana\Http\HttpServiceProvider;
use Zana\Routing\RoutingServiceProvider;
use Zana\Security\SecurityServiceProvider;
use Zana\Translation\TranslationServiceProvider;
use Zana\View\ViewServiceProvider;

/**
 * The application: the framework's providers here, the features in the
 * modules listed in config/modules.php.
 */
final class Bootstrap
{
    public static function builder(string $basePath): ApplicationBuilder
    {
        return Application::configure($basePath)
            ->withProviders(
                HttpServiceProvider::class,
                RoutingServiceProvider::class,
                CacheServiceProvider::class,
                TranslationServiceProvider::class,
                SecurityServiceProvider::class,
                ViewServiceProvider::class,
                ConsoleServiceProvider::class,
            )
            ->withServices(static fn (ServiceCollectionInterface $services) => $services
                ->singleton(ReleaseBuildCommand::class)
                ->tag(ConsoleKernel::TAG, ReleaseBuildCommand::class));
    }
}
