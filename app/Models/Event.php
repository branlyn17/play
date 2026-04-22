<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_user_id',
        'title',
        'description',
        'starts_at',
        'ends_at',
        'timezone',
        'venue_name',
        'address_line',
        'city',
        'region',
        'country',
        'location_url',
        'latitude',
        'longitude',
        'cover_image_path',
        'contact_name',
        'contact_email',
        'contact_phone',
        'guest_capacity',
        'privacy',
        'status',
        'published_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'published_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
