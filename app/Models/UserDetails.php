<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'profile_pic',
        'description',
        'created_at',
    ];

    protected $casts = [
       'description' => 'array',
    ];

    // datetime
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
