<?php

namespace App\Algorithms;

use App\Models\Triage;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Email
{
    public static function send():void
    {
        foreach (Triage::all() as $triage) {

            if (TriageQueue::queuePosition($triage->compte->user) == 2) {
                if ($triage->email_recu == 0) {
                    Log::info($triage->email_recu);
                    Mail::send('emails.changement-queue', ['user' => $triage], function ($message) use ($triage) {
                        $message
                            ->to($triage->compte->user->email)
                            ->subject('Changement de votre position dans la file d\'attente');
                    });
                    Triage::where('compte_id', $triage->compte->id)->update(['email_recu' => 1]);
                }
            } else {
                Triage::where('compte_id', $triage->compte->id)->update(['email_recu' => 0]);
            }
        }
    }
}
