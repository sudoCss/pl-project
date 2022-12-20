<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';

    protected $primaryKey = 'id';

    protected $fillable = [
        'quantity',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
