<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'logo_path'];

    // Relationship: A show can have many schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
