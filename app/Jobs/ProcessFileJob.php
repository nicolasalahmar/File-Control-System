<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;


class ProcessFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $fileId;

    public function __construct($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->acquireLock()) {
            dd("Failed To Get The File");
            return;
        }

        dump("File Processed");


        // Release the lock
        $this->releaseLock();
    }

    private function acquireLock(): bool
    {
        $lockKey = "file_lock:$this->fileId";
        $lockValue = "processing-in-progress";

        $isLockAcquired = Redis::setnx($lockKey, $lockValue);

        // if ($isLockAcquired) {
        //     Redis::expire($lockKey, LOCK_TIMEOUT); // Set lock expiration time
        // }

        return $isLockAcquired;
    }

    private function releaseLock(): void
    {
        $lockKey = "file_lock:$this->fileId";

        Redis::del($lockKey);
    }
}
