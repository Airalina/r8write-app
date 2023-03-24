<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteSend extends Mailable
{
    use Queueable, SerializesModels;
    public $quote;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $quote = [
            'date' => $this->quote->date,
            'description' => $this->quote->description,
        ];
        $emailQuote = $this->markdown('emails.quote', ['quote' => $quote]);
        return $emailQuote;
    }
}
