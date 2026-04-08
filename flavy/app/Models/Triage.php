<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OutOfBoundsException;
use Throwable;

class Triage extends Model
{
    protected $fillable = ['compte_id', 'position', 'priority', 'infirmier_connecte_id', 'medecin_connecte_id'];

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    public function infirmier(): BelongsTo
    {
        return $this->belongsTo(Infirmier_Connecte::class);
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin_Connecte::class);
    }

    public static function enqueue(Compte $compte, int $priority): void
    {
        try {
            DB::transaction(function () use ($compte, $priority) {
                $last = Triage::lockForUpdate()->max('position') ?? 0;

                $data = [
                    'compte_id' => $compte->id,
                    'position' => $last + 1,
                    'priority' => $priority,
                ];

                if ($priority == 0) {
                    $infirmier = Infirmier_Connecte::withCount('triages')->orderBy('triages_count')->first();
                    if (!$infirmier) {
                        throw new Exception("No practitioner available");
                    }
                    $data['infirmier_connecte_id'] = $infirmier->id;
                } else {
                    $medecin = Medecin_Connecte::withCount('triages')->orderBy('triages_count')->first();
                    if (!$medecin) {
                        throw new Exception("No practitioner available");
                    }
                    $data['medecin_connecte_id'] = $medecin->id;
                }

                $triage = new Triage();
                $triage->fill($data);
                $triage->save();
            });
        } catch (Throwable $e) {
            dd($e->getMessage());
            Log::error($e->getMessage());
        }
    }

    public static function dequeue(Compte $compte): void
    {
        $patient = Triage::where('compte_id', $compte->id)->findOrFail();

        if (!$patient) {
            throw new OutOfBoundsException("Queue is empty");
        }

        $patient->delete();
    }

    public static function rebalance(bool $infirmier): void
    {
        $practitioners = $infirmier ? Infirmier_Connecte::withCount('triages')->get() : Medecin_Connecte::withCount('triages')->get();
        if ($practitioners->isEmpty()) return;

        $triages = $infirmier ?
            Triage::orderBy('priority')
            ->orderBy('position')
            ->where('infirmier_connecte_id', null)
            ->get() :
            Triage::orderBy('priority')
            ->orderBy('position')
            ->where('medecin_connecte_id', null)
            ->get();

        $i = 0;
        $count = $practitioners->count();

        foreach ($triages as $triage) {
            $practitioner = $practitioners[$i];

            if ($infirmier) {
                $triage->infirmier_connecte_id = $practitioner->id;
            } else {
                $triage->medecin_connecte_id = $practitioner->id;
            }

            $triage->save();

            $i = ($i + 1) % $count;
        }
    }

    public static function peek(int $index, Compte $practitioner): Compte
    {
        $medecin = Medecin_Connecte::where('compte_id', $practitioner->id)->first();
        $infirmier = Infirmier_Connecte::where('compte_id', $practitioner->id)->first();

        $triage = null;

        if ($infirmier !== null) {
            $triage = Triage::orderBy('priority')
                ->orderBy('position')
                ->where('infirmier_connecte_id', $infirmier->id)
                ->skip($index)
                ->first();
        }
        else if ($medecin !== null) {
            $triage = Triage::orderBy('priority')
                ->orderBy('position')
                ->where('medecin_connecte_id', $medecin->id)
                ->skip($index)
                ->first();
        }

        if (!$triage) {
            throw new OutOfBoundsException("Index out of bounds");
        }

        return $triage->compte;
    }

    public static function count(): int
    {
        return Triage::count();
    }
}
