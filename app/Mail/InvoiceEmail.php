<?php

namespace App\Mail;

use App\Models\Modules\Invoices\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        $companyName = $this->invoice->location->company->name ?? 'Your Company';

        return $this->subject("Your Invoice from {$companyName}")
                    ->view('emails.invoices.invoice');
    }
}
