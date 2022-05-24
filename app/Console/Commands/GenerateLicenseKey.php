<?php

namespace App\Console\Commands;

use App\Models\LicenseKey;
use Illuminate\Console\Command;

class GenerateLicenseKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licensekey:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $key = LicenseKey::create();
        $this->info("UUID: " . $key->id);
        return Command::SUCCESS;
    }
}
