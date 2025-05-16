<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'content',
        'media'
    ];
    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function reactions()
    {
        return $this->hasMany(PostReaction::class);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
