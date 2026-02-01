<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'phone_number',
        'address',
        'age',
        'profile_photo_path',
        'telegram_chat_id',
        'telegram_link_token',
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

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function friends()
    {
        $userId = $this->id;
        
        // Get all friendship IDs where this user is involved and status is accepted
        $ids = \Illuminate\Support\Facades\DB::table('friendships')
            ->where('status', 'accepted')
            ->where(function($q) use ($userId) {
                $q->where('requester_id', $userId)
                  ->orWhere('addressee_id', $userId);
            })
            ->get()
            ->flatMap(function($f) use ($userId) {
                return [$f->requester_id, $f->addressee_id];
            })
            ->filter(function($id) use ($userId) {
                return $id != $userId;
            })
            ->unique()
            ->values()
            ->toArray();

        // Return a query builder for the users
        return User::whereIn('id', $ids);
    }
    
    // Pending requests sent by this user
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'requester_id')->where('status', 'pending');
    }

    // Pending requests received by this user
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'addressee_id')->where('status', 'pending');
    }

    public function isFriendWith($userId)
    {
        return Friendship::where(function($q) use ($userId) {
            $q->where('requester_id', $this->id)->where('addressee_id', $userId);
        })->orWhere(function($q) use ($userId) {
            $q->where('requester_id', $userId)->where('addressee_id', $this->id);
        })->where('status', 'accepted')->exists();
    }
    
    public function hasPendingRequestWith($userId)
    {
        return Friendship::where(function($q) use ($userId) {
            $q->where('requester_id', $this->id)->where('addressee_id', $userId);
        })->orWhere(function($q) use ($userId) {
            $q->where('requester_id', $userId)->where('addressee_id', $this->id);
        })->where('status', 'pending')->exists();
    }
}
