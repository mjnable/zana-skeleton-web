<?php

declare(strict_types=1);

namespace Modules\Site\Presentation\Web;

use Zana\Routing\UrlGenerator;
use Zana\Translation\TranslatorInterface;
use Zana\View\PageMeta;
use Zana\View\View;

final readonly class PageController
{
    public function __construct(private PageMeta $meta, private UrlGenerator $urls, private TranslatorInterface $translator)
    {
    }

    public function home(): View
    {
        return $this->page('home');
    }

    public function about(): View
    {
        return $this->page('about');
    }

    /**
     * The template, and the page's metadata: its title, description, canonical URL and the same page
     * in the other languages.
     */
    private function page(string $name): View
    {
        $this->meta
            ->title($this->translator->translate("{$name}.title"))
            ->description($this->translator->translate("{$name}.description"))
            ->canonical($this->urls->generate($name))
            ->alternates($this->urls->alternates($name), default: 'en');

        return View::make("@Site/{$name}", ['page' => $name]);
    }
}
