<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    public $timestamps = true; 
    protected $table = 'news_table';

    protected $fillable = [
        'title',
        'description',
        'posedBy',
        'thumbnailPath'
    ];

    /**
     * Relationship to the User model
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'posedBy');
    }
}
