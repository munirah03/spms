<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeStaleUploads extends Command
{
    protected $signature = 'uploads:purge';
    protected $description = 'Delete uploaded customer files older than the retention period (UPLOAD_RETENTION_HOURS)';

    public function handle()
    {
        $cutoff = now()->subHours(config('app.upload_retention_hours'))->getTimestamp();
        $deleted = 0;

        foreach (Storage::files('private_uploads') as $file) {
            if (basename($file) === '.gitignore') {
                continue;
            }

            if (Storage::lastModified($file) < $cutoff) {
                Storage::delete($file);
                $deleted++;
            }
        }

        $this->info("Purged {$deleted} expired upload(s).");
    }
}
