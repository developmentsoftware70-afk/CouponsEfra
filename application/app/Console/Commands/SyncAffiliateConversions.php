<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AffiliateSyncService;

class SyncAffiliateConversions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliate:sync {--setting= : Specific PostbackSetting ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync affiliate conversion statuses and missed orders from configured network APIs';

    /**
     * Execute the console command.
     */
    public function handle(AffiliateSyncService $syncService)
    {
        $this->info("Starting Affiliate Network API Sync...");

        $settingId = $this->option('setting') ? (int) $this->option('setting') : null;
        $results = $syncService->syncAll($settingId);

        if (empty($results)) {
            $this->warn("No active network sync configurations found (check 'sync_enabled' in Postback Manager).");
            return Command::SUCCESS;
        }

        foreach ($results as $netName => $res) {
            $statusEmoji = ($res['status'] === 'success') ? '✅' : '❌';
            $this->line("{$statusEmoji} [{$netName}]: {$res['message']}");
        }

        $this->info("Affiliate API Sync completed successfully.");
        return Command::SUCCESS;
    }
}
