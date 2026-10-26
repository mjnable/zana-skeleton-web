<?php

declare(strict_types=1);

namespace App\Console;

use App\Release\ReleaseBuilder;
use App\Release\ReleaseException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zana\Console\Command;
use Zana\Core\ApplicationInterface;

/**
 * php bin/console release:build [--folder=website] [--ref=HEAD]
 *
 * Writes build/<folder>.zip and build/public_html.zip from a commit, with the
 * production dependencies installed. See ReleaseBuilder.
 */
#[AsCommand(name: 'release:build', description: 'Build the upload archives for shared hosting from a commit')]
final class ReleaseBuildCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('folder', null, InputOption::VALUE_REQUIRED, 'The folder that holds the project on the server, beside public_html', 'website')
            ->addOption('ref', null, InputOption::VALUE_REQUIRED, 'The commit, branch or tag to build', 'HEAD')
            ->addOption('output', null, InputOption::VALUE_REQUIRED, 'Where to write the archives', 'build')
            ->addOption('composer', null, InputOption::VALUE_REQUIRED, 'The Composer command, or the path of composer.phar', getenv('COMPOSER_BINARY') ?: 'composer');
    }

    public function handle(InputInterface $input, SymfonyStyle $io, ApplicationInterface $application): int
    {
        $folder = (string) $input->getOption('folder');
        $output = (string) $input->getOption('output');
        $output = preg_match('#^([A-Za-z]:)?[/\\\\]#', $output) === 1 ? $output : $application->path($output);

        try {
            $archives = (new ReleaseBuilder($application->path(), (string) $input->getOption('composer')))
                ->build($output, $folder, (string) $input->getOption('ref'));
        } catch (ReleaseException $e) {
            $io->error($e->getMessage());

            return self::FAILURE;
        }

        $io->success(['Built:', $archives['code'], $archives['public']]);
        $io->listing([
            "Unpack {$folder}.zip beside public_html, so that the project is in ~/{$folder}.",
            'Unpack public_html.zip into public_html.',
            "Create ~/{$folder}/.env with APP_ENV=production, APP_APP__URL and APP_SECURITY__SECRET.",
            "Delete ~/{$folder}/var/cache after each upload, so the container is compiled again.",
        ]);

        return self::SUCCESS;
    }
}
