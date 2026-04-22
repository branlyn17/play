<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateMetricEvent extends Model
{
    use HasFactory;

    public const TYPE_VIEW = 'view';
    public const TYPE_USE = 'use';
    public const TYPE_DOWNLOAD = 'download';

    protected $fillable = [
        'template_id',
        'invitation_id',
        'event_type',
        'locale',
        'country_code',
        'country_name',
        'region_code',
        'region_name',
        'city',
        'timezone',
        'latitude',
        'longitude',
        'accuracy_radius_km',
        'ip_hash',
        'ip_version',
        'user_agent_hash',
        'device_type',
        'browser',
        'platform',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
