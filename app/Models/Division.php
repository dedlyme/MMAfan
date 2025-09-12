<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relācija ar ranking
    public function rankings()
    {
        return $this->hasMany(Ranking::class);
    }

}
