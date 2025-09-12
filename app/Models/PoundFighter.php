<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoundFighter extends Model
{
    use HasFactory;

    protected $fillable = ['fighter_name', 'rank'];
}
