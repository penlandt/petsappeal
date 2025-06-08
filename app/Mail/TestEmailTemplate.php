<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class TestEmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public string $htmlContent;
    public string $plainContent;
    public string $subjectLine;

    public function __construct(string $subjectLine, string $htmlContent, string $plainContent)
    {
        $this->subjectLine = $subjectLine;
        $this->htmlContent = $htmlContent;
        $this->plainContent = $plainContent;
    }

    public function build()
    {
        $this->subject($this->subjectLine);

        return $this->withSymfonyMessage(function (Email $message) {
            $message
                ->html($this->htmlContent)
                ->text($this->plainContent);
        });
    }
}
