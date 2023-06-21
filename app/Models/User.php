<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'file',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    # Users Relationships to Friends

    /**
     * Get the user friends associated with the user.
     */
    public function friends()
    {
        return $this->hasMany(UserFriends::class, 'user_id', 'id');
    }

    /**
     * Get the followers associated with the user.
     */
    public function requestFromFriends()
    {
        return $this->hasMany(UserFriends::class, 'friend_id', 'id')->where('blocked', false)->where('accepted', false)->where('friend', true);
    }

    public function requestMadeToFriends()
    {
        return $this->hasMany(UserFriends::class, 'user_id', 'id')->where('blocked', false)->where('accepted', false)->where('friend', true);
    }

    /**
     * Get the followers associated with the user.
     */
    public function followers()
    {
        return $this->hasMany(UserFriends::class, 'friend_id', 'id')->where('follow', true);
    }

    public function following()
    {
        return $this->friends()->where('follow', true)->where('blocked', false);
    }

    public function blocked()
    {
        return $this->friends()->where('blocked', true);
    }


    public function scopeNumberOf($query)
    {
        return $query->select(['id'])->value('id');
    }



    # Users Actions

    /**
     * Follow a user
     */
    public function followUser($user)
    {
        $this->friends()->createFollowRequest($user);
    }

    /**
     * unfollow a user
     */
    public function unfollowUser($user)
    {
        $this->friends()->where('friend_id', $user->id)->update(['follow', false]);
    }

    /**
     * Send a friend request
     */
    public function createFriendRequest($user)
    {
        return $this->friends()->createFriendRequest($user);
    }

    /**
     * Accept a friend request
     */
    public function acceptFriendRequest($user)
    {
        return $this->friends()->acceptFriendRequest($user);
    }

    /**
     * Block a user
     * @param $user
     */
    public function blockFriend($user)
    {
        return $this->friends()->where('friend_id', $user->id)->update(['blocked' => true]);
    }

    /** Get users that are not friends */
    public function scopeWhereNotFriends($query, $user = null)
    {
        $user = $user ?? auth()->user();
        return $query->whereNotIn('id', $user->friends()->pluck('friend_id'));
    }

}
