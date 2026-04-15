<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitedGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'name',
        'email',
        'phone',
        'status',
        'seat',
        'visited_at',
        'responded_at',
        'last_ip',
        'last_user_agent'
    ];

    public function invitation() {
        return $this->belongsTo(Invitation::class);
    }
}
