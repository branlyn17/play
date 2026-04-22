<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'template_id',
        'title',
        'description',
        'locale',
        'edit_token',
        'public_token',
        'share_slug',
        'status',
        'customization_data',
        'style_overrides',
        'editor_state',
        'rendered_html_path',
        'rendered_html_checksum',
        'view_count',
        'download_count',
        'last_viewed_at',
        'last_downloaded_at',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'customization_data' => 'array',
        'style_overrides' => 'array',
        'editor_state' => 'array',
        'last_viewed_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function guests()
    {
        return $this->hasMany(InvitedGuest::class);
    }

    public function mediaItems()
    {
        return $this->hasMany(InvitationMediaItem::class)->orderBy('sort_order');
    }
}
