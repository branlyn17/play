<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'sort_order',
        'is_active',
    ];

    public function translations()
    {
        return $this->hasMany(InvitationCategoryTranslation::class);
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}
