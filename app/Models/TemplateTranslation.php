<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'locale',
        'name',
        'slug',
        'teaser',
        'description',
        'seo_title',
        'seo_description',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
