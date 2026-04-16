<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationCategoryTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_category_id',
        'locale',
        'name',
        'slug',
        'description',
        'seo_title',
        'seo_description',
    ];

    public function category()
    {
        return $this->belongsTo(InvitationCategory::class, 'invitation_category_id');
    }
}
