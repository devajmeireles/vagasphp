<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Builder, Model};

/**
 * @mixin IdeHelperCandidacy
 */
class Candidacy extends Model
{
    use HasFactory;
    use BelongsToUser;

    protected $guarded = ['id'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
