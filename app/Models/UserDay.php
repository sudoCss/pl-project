<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDay extends Model
{
    use HasFactory;


    protected $table = 'user_days';

    protected $primaryKey = 'id';

    protected $fillable = [
        'start_date',
        'end_date',
        'user_id',
        'day_id',
    ];

    public $timestamps = true;

}
