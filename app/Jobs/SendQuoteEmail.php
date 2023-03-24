<?php

namespace App\Jobs;

use App\Mail\QuoteSend;
use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendQuoteEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $quote, $services;
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function handle()
    {
        $email = $this->quote->owner->email;
        $mail = Mail::to($email);
        $mail->send(new QuoteSend($this->quote));
    }
}
