<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $fillable = ['position', 'reports_to'];

    public function parent(){
        return $this->belongsTo(Position::class, 'reports_to');
    }

    public function childPositions()
    {
        return $this->hasMany(Position::class, 'reports_to');
    }
}
