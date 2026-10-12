<?php

namespace App\Console\Commands;

use App\Models\ArchivedOrder;
use Illuminate\Console\Command;

class PurgeArchivedOrders extends Command
{
    protected $signature = 'archives:purge';
    protected $description = 'Delete archived order records (with customer PII) older than the retention period, keeping only the aggregate daily_reports';

    public function handle()
    {
        $days = (int) config('app.archive_retention_days');
        $cutoff = now()->subDays($days)->toDateString();

        $deleted = ArchivedOrder::where('report_date', '<', $cutoff)->delete();

        $this->info("Purged {$deleted} archived order record(s) older than {$days} days. Daily report totals are kept.");
    }
}
