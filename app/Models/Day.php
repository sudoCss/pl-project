<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $table = 'days';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_days');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
