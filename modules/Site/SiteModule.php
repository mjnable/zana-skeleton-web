<?php

declare(strict_types=1);

namespace Modules\Site;

use Modules\Site\Presentation\Web\PageController;
use Zana\Container\Contracts\ContainerInterface;
use Zana\Container\Contracts\ServiceCollectionInterface;
use Zana\Core\Module;
use Zana\Routing\Router;
use Zana\Translation\LocaleSwitchAction;

/**
 * The site's pages. Add a page with: php bin/console make:page Site Services
 */
final class SiteModule extends Module
{
    public function name(): string
    {
        return 'Site';
    }

    public function register(ServiceCollectionInterface $services): void
    {
        $services->transient(PageController::class);
        // zana:maker services (make:page and make:controller add lines above; keep this line)
    }

    public function boot(ContainerInterface $container): void
    {
        $router = $container->get(Router::class);
        assert($router instanceof Router);

        $router->localized(['en' => '/', 'fr' => '/'], [PageController::class, 'home'])->name('home')->meta(['sitemap' => true]);
        $router->localized(['en' => '/about', 'fr' => '/a-propos'], [PageController::class, 'about'])->name('about')->meta(['sitemap' => true]);
        $router->get('/locale/{locale}', LocaleSwitchAction::class)->name('locale.switch');
        // zana:maker boot (make:page and make:controller add lines above; keep this line)
    }
}
