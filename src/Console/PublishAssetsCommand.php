<?php

namespace Sajidifti\LaravelCommandCenter\Console;

use Illuminate\Console\Command;

class PublishAssetsCommand extends Command
{
    protected $signature = 'command-center:publish-assets';

    protected $description = 'Publish management panel assets (always overwrites existing files)';

    public function handle(): int
    {
        $this->info('Publishing management panel assets...');

        $this->call('vendor:publish', [
            '--tag' => 'command-center-assets',
            '--force' => true,
        ]);

        $this->info('✓ Assets published successfully!');

        return self::SUCCESS;
    }
}
