<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beat extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'title',
        'slug',
        'premium_file',
        'free_file',
    ];

    protected $hidden=[
        'premium_file',
    ];

    protected $with = [
        'user', 'likes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
