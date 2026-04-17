<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Diagnostique extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'commentaire', 'description', 'date_heure', 'priorite'];

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
