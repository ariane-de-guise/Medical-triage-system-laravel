<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['email', 'password'];

    public function compte()
    {
        return $this->hasOne(Compte::class);
    }

    public function diagnostiques()
    {
        return $this->hasMany(Diagnostique::class, 'patient_id');
    }
}
