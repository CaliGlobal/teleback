<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCast extends Model
{
    use HasFactory;


    protected $table = 'news_cast';

    protected $fillable = [
        'title',
        'description',
        'thumbnailPath',
        'url',
    ];
}
