<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'text', 'file', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function following()
    {
        return $this->belongsToMany(UserFriends::class, 'user_friends', 'user_id', 'friend_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
