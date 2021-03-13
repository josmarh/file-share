<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamUploadNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $fileDetail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileDetail)
    {
        $this->fileDetail = $fileDetail;
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
                        'subscriberName'=>$this->fileDetail['subscriber_name'],
                        'fileName'=> $this->fileDetail['file_name'],
                    ]);
    }
}
