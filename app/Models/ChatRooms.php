<?php

namespace App\Models;

use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ChatRooms extends Model
{
    use HasFactory, HasRelationships, Notifiable, BuildsQueries;

    protected $fillable = [
        'name',
        'user_id',
        'uid',
        'description',
        'image',
        'status',
        'users_list',
        'room_limit',
    ];

    //users list is a json array of user ids
    protected function usersList(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    public $timestamps = true;

    public function messages()
    {
        return $this->hasMany(Messages::class, 'chat_id', 'uid');
    }

    // users list
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateChatId(): string
    {
        return 'CHATID-' . uniqid();
    }

    // add new message to chat
    public function addMessage($message)
    {
        $messageData = [
            'message' => $message,
            'user_id' => $this->user_id,
            'chat_id' => $this->uid,
        ];

        $friends = $this->getFriendsFromUsersList();
        if ($this->room_limit == 2 && count($friends) === 1) {

            $messageData['friend_id'] = $friends[0];
        }

        $this->messages()->create($messageData);
    }

    public function getFriendsFromUsersList(): array
    {
        return array_diff($this->users_list, [$this->user_id]);
    }

    public function scopeWithChatId($query, $chatId, $or = false)
    {
        if ($or) {
            return $query->orWhere('uid', $chatId);
        }
        return $query->where('uid', $chatId);
    }


    public function scopeWithUsersList($query, $users = [], $or = false)
    {
        if ($or) {
            return $query->orWhereJsonContains('users_list', $users);
        }
        return $query->whereJsonContains('users_list', $users);
    }

    public function scopeChatRoomsByList($query, $usersList)
    {
        return $query->with('messages')
                ->where(function ($query) use ($usersList) {
                    foreach ($usersList as $user) {
                        $query->whereJsonContains('users_list', $user);
                    }
                })
                ->where('room_limit', count($usersList));
    }

}
