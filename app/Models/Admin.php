<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = ['name', 'org', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($admin) {
            // Sync the logo column with the products table where orgs match
            \DB::table('products')
                ->where('org', $admin->orgs)
                ->update(['logo' => $admin->logo]);
        });
    }
}
