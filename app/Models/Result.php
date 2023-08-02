<?php

namespace App\Models;

use App\Enums\JobResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Builder, Model};

/**
 * @mixin IdeHelperResult
 */
class Result extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => JobResult::class,
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
