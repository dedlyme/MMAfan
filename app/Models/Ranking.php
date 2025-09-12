<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'fighter_name',
        'rank',
        'is_champion', // jauns lauks
    ];
    

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
