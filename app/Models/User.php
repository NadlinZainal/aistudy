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

    public function getFriendsList()
    {
        $userId = $this->id;
        
        // NUCLEAR OPTION: Raw SQL with bindings to prevent ANY parsing errors
        // We use explicit bindings for IDs, but hardcode 'accepted' to be absolutely safe
        $results = \Illuminate\Support\Facades\DB::select(
            "SELECT * FROM friendships WHERE status = 'accepted' AND (requester_id = ? OR addressee_id = ?)", 
            [$userId, $userId]
        );
            
        $ids = [];
        foreach($results as $row) {
            $ids[] = ($row->requester_id == $userId) ? $row->addressee_id : $row->requester_id;
        }
        $ids = array_unique($ids);

        // Return a Collection directly
        return User::whereIn('id', $ids)->get();
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
