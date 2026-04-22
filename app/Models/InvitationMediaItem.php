<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationMediaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'role',
        'url',
        'alt_text',
        'caption',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
