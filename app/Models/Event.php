<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_date',
        'timezone',
        'location',
        'latitude',
        'longitude',
        'capacity',
        'is_public'
    ];

    // Relación con User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relación con Invitations
    public function invitations() {
        return $this->hasMany(Invitation::class);
    }
}
