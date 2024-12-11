<?php

namespace App\Jobs;

use App\Models\Courier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivateCourierJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $courierId;

    /**
     * Create a new job instance.
     *
     * @param int $courierId
     */
    public function __construct($courierId)
    {
        $this->courierId = $courierId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $courier = Courier::find($this->courierId);

        if ($courier && $courier->status === 'paused') {
            $courier->status = 'active';
            $courier->save();
        }
    }
}
