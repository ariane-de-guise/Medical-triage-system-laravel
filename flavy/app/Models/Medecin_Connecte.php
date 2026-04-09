<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Medecin_Connecte extends Model
{
    protected $fillable = ['compte_id'];
    protected $table = 'medecins_connectes';

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    public function triages()
    {
        return $this->hasMany(Triage::class, 'medecin_connecte_id');
    }

    public function login(Compte $compte): void {
        try {
            DB::transaction(function () use ($compte) {
                Medecin_Connecte::create([
                    'compte_id' => $compte->id
                ]);

                Triage::rebalance(false);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    public function logout(Compte $compte): void {
        try {
            DB::transaction(function () use ($compte) {
                $medecin = Medecin_Connecte::where('compte_id', $compte->id)->firstOrFail();
                $medecin->triages()->update(['medecin_connecte_id' => null]);
                $medecin->delete();
                Triage::rebalance(false);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    public function queueCount(Compte $compte): int {
        $medecin = Medecin_Connecte::where('compte_id', $compte->id)->firstOrFail();
        return $medecin->triages()->count();
    }
}
