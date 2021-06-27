<?php

namespace App\Console\Commands;

use App\Models\Cargo;
use Illuminate\Console\Command;

class FixMissingGameItemTranslationIDsInCargos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix-git-ids-in-cargos';

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
        foreach(Cargo::all() as $cargo){
            if($cargo->game_item_translation_id == null) {
                $cargo->game_item_translation_id = "cargo." . $cargo->id;
                $cargo->save();
                echo "Fix translation ID for " . $cargo->id;
            }
        }
        return 0;
    }
}
