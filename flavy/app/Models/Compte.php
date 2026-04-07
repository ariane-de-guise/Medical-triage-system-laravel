<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compte extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function hasRole(string $role): bool
    {
        return $this->user?->hasRole($role) ?? false;
    }
    public function adresses(): BelongsToMany
    {
        return $this->belongsToMany(Adresse::class, 'adresse_user')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function telephones(): BelongsToMany
    {
        return $this->belongsToMany(Telephone::class, 'telephone_user');
    }

    public function visitesPatient(): HasMany
    {
        return $this->hasMany(Visite::class, 'patient_id');
    }

    public function visitesDocteur(): HasMany
    {
        return $this->hasMany(Visite::class, 'docteur_id');
    }
}
