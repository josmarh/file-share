<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserUploadNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $fileDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileDetails)
    {
        $this->fileDetails = $fileDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@oandbservices.com','Fileshare')
                    ->subject('New File Upload')
                    ->markdown('mails.user-upload-notification')
                    ->with([
                        'userName'=>$this->fileDetails['user_name'],
                        'fileName'=>$this->fileDetails['file_name'],
                    ]);
    }
}
