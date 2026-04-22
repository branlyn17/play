<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateMetricDailySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'metric_date',
        'event_type',
        'locale',
        'country_code',
        'region_code',
        'city',
        'total',
    ];

    protected $casts = [
        'metric_date' => 'date',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
