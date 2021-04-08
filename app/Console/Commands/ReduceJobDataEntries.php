<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReduceJobDataEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reducejobdataentries';

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
        foreach(\App\Models\Job::all() as $job){
            echo "reducing data for ".$job->id."...";
            $data = $job->job_data_entries()->get();
            $count = $data->count();
            if($count > 60){
                //reduce models
                $delta = floor($count/60);
                for($currentStage = 0; $currentStage < $count; $currentStage = $currentStage + $delta){
                    $i = $currentStage + 1;
                    while($i < $currentStage + $delta && $i < $count){
                        $data[$i]->delete();
                        $i++;
                    }
                }
            }
            echo "done\n";
        }

        return 0;
    }
}
