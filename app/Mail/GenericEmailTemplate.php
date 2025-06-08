<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericEmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $htmlContent;
    public $plainContent;

    /**
     * Create a new message instance.
     *
     * @param string $subjectLine
     * @param string $htmlContent
     * @param string $plainContent
     */
    public function __construct($subjectLine, $htmlContent, $plainContent)
    {
        $this->subjectLine = $subjectLine;
        $this->htmlContent = $htmlContent;
        $this->plainContent = $plainContent;
    }

    /**
     * Build the message.
     */
    public function build()
{
    return $this
        ->subject($this->subjectLine)
        ->html($this->htmlContent)
        ->text('emails.generic-plain');
}
}
