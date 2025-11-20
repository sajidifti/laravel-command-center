<?php

namespace Sajidifti\LaravelCommandCenter\Console;

use Illuminate\Console\Command;

class CleanSessionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command-center:clean-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired Command Center session files';

    public function handle()
    {
        $this->info('Cleaning expired Command Center sessions...');

        $sessionPath = config('command-center.session.path');
        $files = glob($sessionPath . '/*.json');
        $cleaned = 0;
        $now = time();

        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            }

            // Read session data
            $content = file_get_contents($file);
            $data = json_decode($content, true);

            // Delete if expired or invalid
            if (
                !$data ||
                !is_array($data) ||
                (isset($data['_expire_at']) && $now > $data['_expire_at'])
            ) {
                @unlink($file);
                $cleaned++;
            }
        }

        $this->info("✓ Cleaned {$cleaned} expired session(s)");

        return self::SUCCESS;
    }
}
