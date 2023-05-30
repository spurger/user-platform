<?php

namespace App\Models;

use App\Models\FriendRequest;
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
        'password' => 'hashed',
    ];

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function acceptableFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'recipient_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend', 'user_id', 'friend_id')
            ->withTimestamps();
    }

    public function addFriend($friendId)
    {
        $this->friends()->attach([
            ['user_id' => $this->id, 'friend_id' => $friendId],
            ['user_id' => $friendId, 'friend_id' => $this->id],
        ]);
    }

    /**
     * Note: this function makes two DB calls.
     */
    public function removeFriend(User $friend)
    {
        $this->friends()->detach(['friend_id' => $friend->id]);
        $friend->friends()->detach(['friend_id' => $this->id]);
    }
}
