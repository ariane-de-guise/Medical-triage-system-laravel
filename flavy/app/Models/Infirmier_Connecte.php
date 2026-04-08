<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class Infirmier_Connecte extends Model
{
    protected $fillable = ['compte_id'];
    protected $table = 'infirmiers_connectes';

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    public function triages()
    {
        return $this->hasMany(Triage::class, 'infirmier_connecte_id');
    }

    public function login(Compte $compte): void {
        try {
            DB::transaction(function () use ($compte) {
                Infirmier_Connecte::create([
                    'compte_id' => $compte->id
                ]);

                Triage::rebalance(true);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    public function logout(Compte $compte): void {
        try {
            DB::transaction(function () use ($compte) {
                $infirmier = Infirmier_Connecte::where('compte_id', $compte->id)->firstOrFail();
                $infirmier->triages()->update(['infirmier_connecte_id' => null]);
                $infirmier->delete();
                Triage::rebalance(true);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    public function queueCount(Compte $compte): int {
        $infirmier = Infirmier_Connecte::where('compte_id', $compte->id)->firstOrFail();
        return $infirmier->triages()->count();
    }
}
