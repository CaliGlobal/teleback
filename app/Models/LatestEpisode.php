<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestEpisode extends Model
{
    use HasFactory;

    protected $table = 'latest_episode';

    protected $fillable = [
        'show_id',
        'description',
        'thumbnailPath',
        'url',
    ];

    /**
     * Define the relationship with the Shows model
     */
    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }
}
