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

    // This is a very intense job. Timeout after 10 minutes
    public $timeout = 10 * 60;

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

        // only retrieve jobs, where data entries are newer than 24 hours + 1 hour safe margin
        $jobs = Job::whereRelation(
            'job_data_entries', 'created_at', '>=', now()->subHours(25)
        )->get();

        foreach ($jobs as $job) {
            $data = $job->job_data_entries()->get();
            $count = $data->count();

            // loop, but don't check first and last element
            for ($i = 1; $i < $count - 1; $i++) {
                if ($data[$i - 1]->current_speed_kph == $data[$i]->current_speed_kph) {
                    $data[$i]->delete();
                }
            }
        }

        $current_sum_of_entries = JobDataEntry::all()->count();

        Log::info("MinimizeJobDataEntries: Reduced count of job data entries from $previous_sum_of_entries to $current_sum_of_entries");
    }
}
