<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dreamfight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fighter_one_name',
        'fighter_two_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
