<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dreamfight extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_one_id',
        'player_two_id',
        'player_one_fighter_id',
        'player_two_fighter_id',
        'status',
        'current_round',
        'player_one_score',
        'player_two_score',
        'player_one_choice',
        'player_two_choice',
        'winner'
    ];

    public function playerOne()
    {
        return $this->belongsTo(User::class, 'player_one_id');
    }

    public function playerTwo()
    {
        return $this->belongsTo(User::class, 'player_two_id');
    }

    /** ✅ NEW: fighter relations */
    public function fighterOne()
    {
        return $this->belongsTo(Fighter::class, 'player_one_fighter_id');
    }

    public function fighterTwo()
    {
        return $this->belongsTo(Fighter::class, 'player_two_fighter_id');
    }
}
