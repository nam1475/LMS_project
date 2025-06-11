<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image',
        'approve_status',
        'document',
        'enmail_verified_at',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function receivesBroadcastNotificationsOn(): string {
    //     return "notification.{$this->id}";
    // }

    function orders() : HasMany {
        return $this->hasMany(Order::class, 'buyer_id', 'id');
    }

    function courses() : HasMany {
        return $this->hasMany(Course::class, 'instructor_id', 'id');
    }


    function gatewayInfo() : HasOne {
       return $this->hasOne(InstructorPayoutInformation::class, 'instructor_id', 'id');
    }


    function students() : HasMany {
        return $this->hasMany(Enrollment::class, 'instructor_id', 'id');
    }

    function reviews() : HasMany {
       return $this->hasMany(Review::class, 'instructor_id', 'id');
    }

    function enrollments() : HasMany{
       return $this->hasMany(Enrollment::class, 'user_id', 'id');
    }


    public function unreadMessages($senderId) {
        return $this->hasMany(Chat::class, 'receiver_id', 'id')->where(['is_read' => false, 'sender_id' => $senderId]);
    }

    // public function readMessages($senderId) {
    //     return $this->hasMany(Chat::class, 'receiver_id', 'id')->where('sender_id', $senderId)
    //                 ->where('is_read', false)->update(['is_read' => true]);
    // }

    // public function messages($senderId) : HasMany {
    //     return $this->hasMany(Chat::class, 'receiver_id', 'id')->where('sender_id', $senderId);
    // }
}
