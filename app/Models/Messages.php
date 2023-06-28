<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Messages extends Model
{
    use HasFactory, HasRelationships, Notifiable;

    protected $fillable = [
        'user_id',
        'friend_id',
        'user_name',
        'chat_id',
        'text',
        'file',
        'read_at',
        'created_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public $file;
    public array $users_list;

    // datetime
    public $timestamps = true;

    // get the chat_id for the user and friend automatically if not set
    public static function boot()
    {
        parent::boot();

        // automatically get chat_id or make a new one in room


        static::creating(function ($model) {
            if (isset($model->user_id)) {
                if (!isset($model->user_name)) {
                    $model->user_name = User::find($model->user_id)->name;
                }
            } else {
                $model->user_id = \Auth::user()->id;
                // get first name of user
                $model->user_name = \Auth::user()->name;
            }

            if (!isset($model->chat_id)) {
                if (isset($model->friend_id)) {

                    $chat_id = ChatRooms::withUsersList([$model->user_id, $model->friend_id])->first('uid');

                    if (!$chat_id) {
                        $chat_id = ChatRooms::generateChatId();
                        // $model->text = $chat_id;

                        self::createChatRoomForUsers($model);
                    }

                    $model->chat_id = $chat_id;
                }
            }
        });
    }

    public function chatRoom()
    {
        return $this->hasOne(ChatRooms::class, 'chat_id', 'uid')->where('user_id', $this->user_id);
    }

    // set user_list appends from chatroom model
    public function users_list()
    {
        return $this->chatRoom()->users_list;
    }


    /** Get the chat_id for the user and friend || Create a new chat_id */
    public static function getChatId($friend_id): ?string
    {
        $user_id = auth()->id();

        // get the chat_id for the user and friend
        return (\DB::table('messages')
            ->where([['user_id', $user_id], ['friend_id', $friend_id]])
            ->OrWhere([['user_id', $friend_id], ['friend_id', $user_id]])
            ->first('chat_id'))?->chat_id; // if it does not exist, create a new chat_id
    }

    /**
     * Create chat rooms for given users.
     *
     * @param int $chat_id chat identifier
     * @param array $users list of user ids
     * @throws \Exception if chat room could not be created
     */
    public static function createChatRoomForUsers($model)
    {
        $users = [$model->user_id, $model->friend_id];

        $uid = ChatRooms::generateChatId();
        $model->chat_id = $uid;

        foreach ($users as $user) {
            ChatRooms::create([
                'user_id' => $user,
                'uid' => $uid,
                'room_limit' => count($users),
                'users_list' => $users,
            ]);
        }
    }


    /**
     * Returns a relationship instance between the current class and the User
     * class. The foreign key column name is 'user_id' and the
     * owner key column name is 'id'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns a relationship instance between the current class and the User
     * class. The foreign key column name is 'friend_id' and the
     * owner key column name is 'id'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    /**
     * Returns a relationship instance between the current class and the User
     * class. The foreign key column name is 'user_id' and the
     * owner key column name is 'id'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function friends()
    {
        return $this->hasMany(User::class, 'friend_id');
    }

    /**
     * Returns a relationship instance between the current class and the User
     * class. The foreign key column name is 'friend_id' and the
     * owner key column name is 'id'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Messages that have been read
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Mark a message as read
     */
    public function scopeMarkRead($query)
    {
        return $query->update(['read_at' => now()]);
    }

    /**
     * Auth user messages
     */
    public function scopeAuth($query, $user = null)
    {
        $user = $user ?? \Auth::user();
        return $query->where('user_id', $user->id);
    }

    /** Get all messages grouped by friend */
    public function scopeGroupByFriend($query)
    {
        return $query->groupBy('friend_id');
        ;
    }

    /** Get all messages grouped by chat_id */
    public function scopeGroupByChat($query)
    {
        return $query->groupBy('chat_id');
        ;
    }

    public function scopeMessages($query, $user_id, $friend_id)
    {
        // $user_id = auth()->id();

        // get the messages for the user and friend
        return $query->where(function ($query) use ($user_id, $friend_id) {
            $query->where([['user_id', $user_id], ['friend_id', $friend_id]])
                ->OrWhere([['user_id', $friend_id], ['friend_id', $user_id]]);
        });
    }

    public function scopeWithChatId($query, $chat_id)
    {
        return $query->where('chat_id', $chat_id);
    }


    public function newChatMessage($text)
    {

        return Messages::create([
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'friend_id' => $this->friend_id,
            'chat_id' => $this->chat_id,
            'text' => $text,
        ]);
    }
}
