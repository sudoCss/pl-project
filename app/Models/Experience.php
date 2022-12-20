<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $table = 'experiences';

    protected $primaryKey = 'id';

    protected $fillable = [
        'details',
        'user_id',
        'speciality_id',
    ];

    public $timestamps = true;
}
