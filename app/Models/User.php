<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $name
 * @property string $role
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isFinance()
    {
        return $this->role === 'finance';
    }

    public function getNamaAttribute()
    {
        return $this->name;
    }

    public function setNamaAttribute($value)
    {
        $this->name = $value;
    }

    public function getPeranAttribute()
    {
        return $this->role;
    }

    public function setPeranAttribute($value)
    {
        $this->role = $value;
    }
}
