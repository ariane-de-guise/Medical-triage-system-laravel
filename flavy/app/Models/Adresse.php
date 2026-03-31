<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adresse extends Model
{
    use HasFactory;

    protected $fillable = ['code_postal', 'numero', 'province', 'rue', 'ville', 'type'];

    public function comptes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Compte::class, 'adresse_user');
    }
}
