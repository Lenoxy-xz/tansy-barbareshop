<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table      = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps    = false;

    protected $fillable = [
        'nama', 'username', 'email', 'no_hp', 'password', 'role', 'foto',
    ];

    protected $hidden = ['password'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_user', 'id_user');
    }

    public function testimoni()
    {
        return $this->hasMany(Testimoni::class, 'id_user', 'id_user');
    }

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isPelanggan(): bool { return $this->role === 'pelanggan'; }
}
