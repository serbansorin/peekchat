<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Chat extends Model
{
    use HasFactory, HasRelationships, Notifiable;

    protected $fillable = [
        'user_id',
        'chat_uid',
        'chat_id',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'users_id' => 'array',
        'users_list' => 'array',
    ];

    private $file;

    // model save to file instead of table
    public function __construct()
    {
        parent::__construct();
        $this->file = storage_path('app/chats.json');


    }
}
