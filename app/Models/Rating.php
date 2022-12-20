<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'stars',
    ];

    public $timestamps = true;

    public function rated()
    {
        return $this->belongsTo(User::class, 'rated');
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater');
    }

}
