<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';

    // protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public $timestamps = true;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function specialities()
    {
        return $this->belongsToMany(Speciality::class, 'experiences');
    }

    public function days()
    {
        return $this->belongsToMany(Day::class, 'user_days');
    }

    public function rateds()
    {
        return $this->hasMany(Rating::class, 'rated');
    }

    public function raters()
    {
        return $this->hasMany(Rating::class, 'rater');
    }

    public function users()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function experts()
    {
        return $this->hasMany(Appointment::class, 'expert');
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }
}
