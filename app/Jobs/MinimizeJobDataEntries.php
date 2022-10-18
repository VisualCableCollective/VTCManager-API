<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\JobDataEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MinimizeJobDataEntries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $previous_sum_of_entries = JobDataEntry::all()->count();
        $jobs = Job::all();
        foreach ($jobs as $job) {
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
        }

        $current_sum_of_entries = JobDataEntry::all()->count();

        Log::info("MinimizeJobDataEntries: Reduced count of job data entries from $previous_sum_of_entries to $current_sum_of_entries");
    }
}
