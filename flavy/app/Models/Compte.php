<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nom', 'prenom', 'date_naissance', 'type'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function adresses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Adresse::class, 'adresse_user')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function telephones(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Telephone::class, 'telephone_user');
    }

    public function visitesPatient()
    {
        return $this->hasMany(Visite::class, 'patient_id');
    }

    public function visitesDocteur()
    {
        return $this->hasMany(Visite::class, 'docteur_id');
    }
}
