<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table      = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing  = true;
    protected $keyType    = 'int';

    protected $fillable = [
        'nama_user',
        'email_user',
        'pass_user',
        'role_user',
    ];

    protected $hidden = [
        'pass_user',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Wajib: Auth pakai pass_user bukan password
    public function getAuthPassword(): string
    {
        return $this->pass_user;
    }

    // Wajib: Auth pakai email_user bukan email
    public function getAuthIdentifierName(): string
    {
        return 'id_user';
    }

    // Remember token pakai kolom yang baru ditambahkan
    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    public function isAdmin(): bool    { return $this->role_user === 'admin'; }
    public function isManager(): bool  { return $this->role_user === 'manager'; }
    public function isKaryawan(): bool { return $this->role_user === 'karyawan'; }
}