<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Mail;
use App\Mail\UserUploadNotification;

class UserMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $fileDetailUser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileDetailUser)
    {
        $this->fileDetailUser = $fileDetailUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->fileDetailUser['user_email'])->send( new UserUploadNotification($this->fileDetailUser));
    }
}
