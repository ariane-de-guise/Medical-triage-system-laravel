<?php

namespace App\Algorithms;

use App\Models\Triage;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class Email
{
    public static function send():void
    {
        $user = self::getPatientQueue(2);
        Mail::send('emails.changement-queue',['user' => $user], function ($message) use ($user) {
            $message->to($user->compte->user->email)
                ->subject('Changement de votre position dans la file d\'attente');

        });
    }

    public static function getPatientQueue(int $index): Triage {

            $triages = Triage::orderBy('priority')
                ->orderBy('position')
                ->get();

            return $triages[$index];
    }
}
