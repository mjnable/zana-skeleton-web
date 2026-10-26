<?php

declare(strict_types=1);

namespace App\Tests;

use App\Release\ReleaseBuilder;
use App\Release\ReleaseException;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[RequiresPhpExtension('zip')]
final class ReleaseBuilderTest extends TestCase
{
    private string $project = '';

    protected function setUp(): void
    {
        $this->project = str_replace('\\', '/', sys_get_temp_dir()) . '/zana-release-test-' . bin2hex(random_bytes(4));
        $files = [
            'composer.json' => '{"name": "acme/site", "require": {}}',
            'public/index.php' => "<?php\nconst PROJECT_FOLDER = 'website';\n",
            'public/assets/site.css' => 'body {}',
            'src/Page.php' => '<?php',
            'tests/PageTest.php' => '<?php',
            'phpunit.xml.dist' => '<phpunit/>',
            '.gitignore' => "/vendor/\n/var/\n.env\n",
        ];
        foreach ($files as $name => $content) {
            @mkdir(dirname("{$this->project}/{$name}"), 0775, true);
            file_put_contents("{$this->project}/{$name}", $content);
        }
        file_put_contents("{$this->project}/.env", 'APP_SECURITY__SECRET=never-shipped');
        file_put_contents("{$this->project}/uncommitted.txt", 'not in the commit');

        $git = 'git -C ' . escapeshellarg($this->project);
        exec("{$git} init -q && {$git} add composer.json public src tests phpunit.xml.dist .gitignore 2>&1 && {$git} -c user.name=Test -c user.email=test@example.com commit -q -m init 2>&1", $out, $status);
        if ($status !== 0) {
            $this->markTestSkipped('git is not available.');
        }
    }

    #[After]
    public function cleanUp(): void
    {
        exec(PHP_OS_FAMILY === 'Windows' ? 'rmdir /s /q ' . escapeshellarg(str_replace('/', '\\', $this->project)) : 'rm -rf ' . escapeshellarg($this->project));
    }

    public function testTheArchivesSplitTheProjectFromItsPublicFolder(): void
    {
        $archives = (new ReleaseBuilder($this->project, self::composer()))->build("{$this->project}/build", 'acme-site');

        $code = self::entries($archives['code']);
        $this->assertContains('acme-site/composer.json', $code);
        $this->assertContains('acme-site/src/Page.php', $code);
        $this->assertContains('acme-site/vendor/autoload.php', $code, 'production dependencies are installed');
        $this->assertContains('acme-site/var/', $code, 'var/ exists, empty');
        $this->assertNotContains('acme-site/tests/PageTest.php', $code);
        $this->assertNotContains('acme-site/phpunit.xml.dist', $code);
        $this->assertNotContains('acme-site/.env', $code, '.env is never committed, so it never ships');
        $this->assertNotContains('acme-site/uncommitted.txt', $code);

        $public = self::entries($archives['public']);
        sort($public);
        $this->assertSame(['assets/site.css', 'index.php'], $public);

        $zip = new \ZipArchive();
        $zip->open($archives['public']);
        $this->assertStringContainsString("const PROJECT_FOLDER = 'acme-site';", (string) $zip->getFromName('index.php'));
        $zip->close();
    }

    public function testFolderNamesCannotEscape(): void
    {
        $this->expectException(ReleaseException::class);

        (new ReleaseBuilder($this->project))->build("{$this->project}/build", '../elsewhere');
    }

    /**
     * @return list<string>
     */
    private static function entries(string $archive): array
    {
        $zip = new \ZipArchive();
        self::assertTrue($zip->open($archive));
        $names = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $names[] = (string) $zip->getNameIndex($i);
        }
        $zip->close();

        return $names;
    }

    private static function composer(): string
    {
        $composer = getenv('COMPOSER_BINARY') ?: 'composer';
        exec((str_ends_with($composer, '.phar') ? escapeshellarg(PHP_BINARY) . ' ' : '') . escapeshellarg($composer) . ' --version 2>&1', $out, $status);
        if ($status !== 0) {
            self::markTestSkipped('Composer is not available: set COMPOSER_BINARY.');
        }

        return $composer;
    }
}
