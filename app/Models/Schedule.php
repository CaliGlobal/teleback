<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['show_id', 'frequency', 'day', 'days', 'time'];

    // Relationship: A schedule belongs to a show
    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    // Accessor to get 'days' as an array
    public function getDaysAttribute($value)
    {
        return json_decode($value, true);
    }

    // Mutator to store 'days' as JSON
    public function setDaysAttribute($value)
    {
        $this->attributes['days'] = json_encode($value);
    }
}
