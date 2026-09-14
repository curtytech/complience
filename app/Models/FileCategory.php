<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'color', 'icon', 'icon_bg'])]
class FileCategory extends Model
{
    use HasFactory;

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'category_id');
    }

    public function getSafeColorAttribute(): string
    {
        return filled($this->color) ? $this->color : 'from-slate-500 to-slate-600';
    }

    public function getSafeIconAttribute(): string
    {
        return filled($this->icon) ? $this->icon : 'fa-folder';
    }

    public function getSafeIconBgAttribute(): string
    {
        return filled($this->icon_bg) ? $this->icon_bg : 'bg-slate-100 text-slate-600';
    }
}
