<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitedGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'access_token',
        'name',
        'email',
        'phone',
        'guest_count',
        'status',
        'seat_label',
        'notes',
        'invited_at',
        'viewed_at',
        'responded_at',
        'last_ip',
        'last_user_agent',
        'response_payload',
    ];

    protected $casts = [
        'invited_at' => 'datetime',
        'viewed_at' => 'datetime',
        'responded_at' => 'datetime',
        'response_payload' => 'array',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
