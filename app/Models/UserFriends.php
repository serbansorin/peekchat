<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Messages;

class UserFriends extends Model
{
    use HasFactory, HasRelationships;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!isset($model->user_id)) {
                $model->user_id = \Auth::user()->id;
            }

            if (!isset($model->accepted)) {
                $model->accepted = false;
            }

            if (!isset($model->blocked)) {
                $model->blocked = false;
            }

            if (!isset($model->friend)) {
                $model->friend = false;
            }

            if (!isset($model->follow)) {
                $model->follow = true;
            }
        });
    }


    protected $fillable = [
        'user_id',
        'friend_id',

        // accepted friend request
        'accepted',
        // blocked friend request
        'blocked',
        // is friend request
        'friend',
        // is follow request
        'follow'
    ];

    protected $casts = [
        'accepted' => 'boolean',
        'blocked' => 'boolean',
        'friend' => 'boolean',
        'follow' => 'boolean',
    ];


    // datetime
    public $timestamps = true;

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }



    public function scopeGetFriendsProfiles($query)
    {
        return User::where('id', '!=', auth()->id())
            ->whereIn('id', function ($query) {
                return
                    $query->select('friend_id')
                        ->from('user_friends')
                        ->where('user_id', auth()->id())
                        ->where('blocked', false)
                        // ->where('accepted', true) #TODO: will enable later when we have tested relationships
                        ->where('friend', true);
            })->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Messages::class);
    }

    /**
     * Scope a query to create a new friend request relationship.
     */
    public function scopeSendRequest($query, $friend, $user = null, $accepted = false, $blocked = false, $follow = true, $isFriend = true)
    {
        $friendId = static::findNumericID($friend);
        $user = $user ?? auth()->user();
        $userId = static::findNumericID($user);

        // create a new relationship
        return $query->updateOrCreate(['user_id' => $userId, 'friend_id' => $friendId], [
            'user_id' => $userId,
            'friend_id' => $friendId,
            'accepted' => $accepted,
            'blocked' => $blocked,
            'friend' => $isFriend,
            'follow' => $follow,
        ]);
    }


    /**
     * Creates a new follow request for the authenticated user and the given friend.
     */
    public function scopeCreateFollowRequest($query, $friend)
    {
        return $query->sendRequest($friend, auth()->user(), false, false, true, false);
    }

    /**
     * Scope a query to create a new friend request relationship.
     */
    public function scopeCreateFriendRequest($query, $friend)
    {
        return $query->sendRequest($friend, auth()->user(), false, false, true, true);
    }

    /** accept a friend request */
    public function scopeAcceptFriendRequest($query, $friend)
    {

        return $query->sendRequest($friend, auth()->user(), true);
    }

    /** block a friend request */
    public function scopeBlockFriendRequest($query, $friend)
    {
        $friendId = static::findNumericID($friend);

        return $query->where('user_id', $friendId)->where('friend_id', auth()->id())->update([
            'accepted' => false,
            'blocked' => true,
            'friend' => true,
            'follow' => true,
        ]);
    }


    /**
     * Returns the numeric ID of a friend or the friend's ID if it's already numeric.
     *
     * @param mixed $friend the friend object or ID to guess
     * @return int the ID of the friend
     */
    public static function findNumericID($friend)
    {
        return is_numeric($friend) ? $friend : $friend->id;
    }

    /**
     * Retrieves the number of records from the query result.
     */
    public function scopeNumberOf($query)
    {
        return $query->select(['id'])->value('id');
    }

    /**
     * Filters the query by the authenticated user's id.
     */
    public function scopeAuth($query)
    {
        return $query->where('user_id', auth()->id());
    }

    /**
     * Scope a query to include only friend requests.
     */
    public function scopeFriendRequest($query)
    {
        return $query->where('friend', true)->where('blocked', false)->where('accepted', false);
    }

    /**
     * Filters the query to only include friend requests to the authenticated user.
     */
    public function scopeRequestToFriends($query)
    {
        return $query->friendRequest()->where('friend_id', auth()->id());
    }

    /**
     * Retrieves friend requests sent to the current authenticated user.
     */
    public function scopeRequestFromFriends($query)
    {
        return $query->friendRequest()->where('user_id', auth()->id());
    }

    /**
     * Filters the query to only include friends.
     */
    public function scopeFriends($query)
    {
        return $query->where('friend', true)->where('blocked', false)->where('accepted', true);
    }

    // extra followers where gets all friends that are following you
    public function scopeFollowing($query)
    {
        return $query->where('follow', true)->where('blocked', '<>', true);
    }

    // extra followers where gets all friends that are following you

    public function scopeFollowers($query)
    {
        return $query->where('follow', false)->where('blocked', '<>', true)->where('friend_id', auth()->id());
    }



}
