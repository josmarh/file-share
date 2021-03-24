<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserUploadNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $fileDetailUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileDetailUser)
    {
        $this->fileDetailUser = $fileDetailUser;
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
                        'userName'=>$this->fileDetailUser['user_name'],
                        'fileName'=>$this->fileDetailUser['file_name'],
                    ]);
    }
}
