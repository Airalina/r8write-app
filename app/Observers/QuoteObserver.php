<?php

namespace App\Observers;

use App\Jobs\SendQuoteEmail;
use App\Models\Quote;

class QuoteObserver
{
    public function created(Quote $quote)
    {
        SendQuoteEmail::dispatch($quote)->delay(now()->addSeconds(10));
    }
}
