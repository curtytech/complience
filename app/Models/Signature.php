<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['file_id', 'name', 'email', 'cpf'])]
class Signature extends Model
{
    use HasFactory;

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
