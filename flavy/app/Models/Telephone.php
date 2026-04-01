<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Telephone extends Model
{
    use HasFactory;

    protected $fillable = ['numero'];
    public function comptes()
    {
        return $this->belongsToMany(Compte::class, 'telephone_user');
    }
}
