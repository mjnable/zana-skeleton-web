<?php

declare(strict_types=1);

namespace App\Tests;

use App\Bootstrap;
use Zana\Core\ApplicationBuilder;
use Zana\Testing\ApplicationTestCase;

final class SiteTest extends ApplicationTestCase
{
    protected function createApplicationBuilder(): ApplicationBuilder
    {
        return Bootstrap::builder(dirname(__DIR__))
            ->withEnvironment('testing')
            ->withConfiguration(['security.secret' => str_repeat('t', 64), 'cache.default' => 'memory', 'cache.stores.memory' => ['driver' => 'array']]);
    }

    public function testTheRootFollowsTheBrowserLanguage(): void
    {
        $this->browser()->get('/', headers: ['Accept-Language' => 'fr-FR,fr;q=0.9'])->assertRedirect('/fr');
        $this->browser()->get('/', headers: ['Accept-Language' => 'de'])->assertRedirect('/en');
    }

    public function testPagesExistInBothLanguagesWithTheirMetadata(): void
    {
        $this->browser()->get('/en/about')
            ->assertOk()
            ->assertSelector('h1', 'About us')
            ->assertSelector('title', 'About us | My website')
            ->assertSelector('link[rel=canonical][href="http://localhost:8000/en/about"]')
            ->assertSelector('link[rel=alternate][hreflang=fr][href="http://localhost:8000/fr/a-propos"]');

        $this->browser()->get('/fr/a-propos')
            ->assertOk()
            ->assertHeader('Content-Language', 'fr')
            ->assertSelector('h1', 'À propos')
            ->assertSelector('a.language[href="/locale/en?to=%2Fen%2Fabout"]', 'English');
    }

    public function testTheLanguageSwitcherRemembersTheChoice(): void
    {
        $response = $this->browser()->get('/locale/fr?to=/fr/a-propos')->assertRedirect('/fr/a-propos');

        $this->assertStringStartsWith('locale=fr;', $response->header('Set-Cookie'));
    }

    public function testAssetsAreVersioned(): void
    {
        $this->browser()->get('/en')->assertSelector('link[rel=stylesheet][href^="/assets/site.css?v="]');
    }

    public function testResponsesCarrySecurityHeaders(): void
    {
        $this->browser()->get('/en')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY');
    }

    public function testMissingPagesAreTranslated(): void
    {
        $this->browser()->get('/fr/nulle-part')->assertStatus(404)->assertSelector('h1', 'Erreur 404');
    }
}
