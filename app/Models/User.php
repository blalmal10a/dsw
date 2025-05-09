<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'department_id',
        'teacher_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function user_roles(){
        return Role_User::where('user_id',$this->id)->get();
    }

    public function max_role_level(){
        return $this->roles->max('level');
    }
    
    public function isWardenOf($hostel_id){
        $role = Role::where('role','Warden')->first();
        return Role_User::where('user_id',$this->id)->where('role_id',$role->id)->where('type','hostel')->where('foreign_id',$hostel_id)->exists();
    }

    public function isWarden(){
        $role = Role::where('role','Warden')->first();
        return Role_User::where('user_id',$this->id)->where('role_id',$role->id)->exists();
    }

    public function isAdmin(){
        $role = Role::where('role','Admin')->first();
        return Role_User::where('user_id',$this->id)->where('role_id',$role->id)->exists();
    }

    
    public function isDsw(){
        $role = Role::where('role','DSW')->first();
        return Role_User::where('user_id',$this->id)->where('role_id',$role->id)->exists();
    }

    public function hasRole($strRole)
    {
        $role = Role::where('role',$strRole)->first();
        return Role_User::where('user_id',$this->id)->where('role_id',$role->id)->exists();
        
    }

    public function hasWardenRole($hostel_id){
        // return $hostel_id;
        $role = Role::where('role','Warden')->first();
        // return $role;
        return Role_User::where('user_id',$this->id)->where('role_id',$role->id)->where('type','hostel')->where('foreign_id',$hostel_id)->exists();
    }

    public function attmasters(){
        return $this->hasMany(Attmaster::class);
    }

}
