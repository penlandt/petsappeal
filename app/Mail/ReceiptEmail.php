<?php

namespace App\Mail;

use App\Models\POS\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\POS\Sale $sale
     */
    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyName = optional($this->sale->location->company)->name ?? 'Your Company';

        return $this->subject("Your Receipt from {$companyName}")
                    ->view('emails.receipt')
                    ->with([
                        'sale' => $this->sale,
                    ]);
    }
}
