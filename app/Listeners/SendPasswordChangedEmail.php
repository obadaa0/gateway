<?php

namespace App\Listeners;

use App\Events\PasswordChanged;
use App\Mail\PasswordChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPasswordChangedEmail
{

    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(PasswordChanged $event): void
    {
        Mail::to($event->user->email)->send(new PasswordChangedMail($event->user));
    }
}
