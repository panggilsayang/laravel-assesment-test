<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_SUPERVISOR = 2;
    const ROLE_EMPLOYEE = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'integer'
    ];

    protected $appends = [
        'role_label'
    ];

    public function getRoleLabelAttribute()
    {
        $roles = $this->rolesData();
        if (isset($this->role) && in_array($this->role,array_keys($roles))) {
            return $roles[$this->role];
        }
        
        return 'Unknown';
    }

    public function rolesData()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_SUPERVISOR => 'Supervisor',
            self::ROLE_EMPLOYEE => 'Employee'
        ];
    }

    public function scopeAssigneeLists($query,$user)
    {
        // $roles = [];
        if ($user->role == self::ROLE_ADMIN) {
            $roles = [self::ROLE_ADMIN,self::ROLE_SUPERVISOR,self::ROLE_EMPLOYEE];
        }
        if ($user->role == self::ROLE_SUPERVISOR) {
            $roles = [self::ROLE_SUPERVISOR,self::ROLE_EMPLOYEE];
        }
        if ($user->role == self::ROLE_EMPLOYEE) {
            $roles = [self::ROLE_EMPLOYEE];
        }
        return $query->whereIn('role',$roles);
    }
}
