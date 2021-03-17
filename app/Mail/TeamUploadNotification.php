<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamUploadNotification extends Mailable
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
                    ->markdown('mails.team-upload-notification')
                    ->with([
                        'subscriberName'=>$this->fileDetails['subscriber_name'],
                        'fileName'=> $this->fileDetails['file_name'],
                        'fileId' => $this->fileDetails['file_id'],
                    ]);
    }
}
