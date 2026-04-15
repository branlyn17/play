<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'file_path',
        'published_url',
        'locale',
        'price',
        'currency',
        'is_paid',
        'is_active'
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function guests() {
        return $this->hasMany(InvitedGuest::class);
    }
}
