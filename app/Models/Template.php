<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_category_id',
        'code',
        'default_locale',
        'preview_image_path',
        'thumbnail_image_path',
        'source_html_path',
        'source_css_path',
        'source_js_path',
        'editor_schema',
        'default_content',
        'design_tokens',
        'available_fonts',
        'available_colors',
        'is_active',
        'is_featured',
        'is_premium',
        'sort_order',
        'view_count',
        'download_count',
        'use_count',
        'published_at',
    ];

    protected $casts = [
        'editor_schema' => 'array',
        'default_content' => 'array',
        'design_tokens' => 'array',
        'available_fonts' => 'array',
        'available_colors' => 'array',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(InvitationCategory::class, 'invitation_category_id');
    }

    public function translations()
    {
        return $this->hasMany(TemplateTranslation::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
