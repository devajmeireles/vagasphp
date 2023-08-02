<?php

namespace App\Models;

use App\Enums\{JobContent, JobModality, JobModel, JobSpecification, JobStatus, JobTypes};
use App\Models\Traits\BelongsToUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};

/**
 * @mixin IdeHelperJob
 */
class Job extends Model
{
    use HasUuids;
    use HasFactory;
    use SoftDeletes;
    use BelongsToUser;

    protected $guarded = ['id'];

    protected $casts = [
        'specification' => JobSpecification::class,
        'type'          => JobTypes::class,
        'status'        => JobStatus::class,
        'content'       => JobContent::class,
        'model'         => JobModel::class,
        'company'       => 'collection',
        'modality'      => JobModality::class,
        'remuneration'  => 'collection',
        'requirement'   => 'collection',
        'configuration' => 'collection',
    ];

    public function candidacy(): HasMany
    {
        return $this->hasMany(Candidacy::class);
    }

    public function result(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function isReview(): bool
    {
        return $this->status === JobStatus::Review;
    }

    public function isActive(): bool
    {
        return $this->status === JobStatus::Actived;
    }

    public function isInactive(): bool
    {
        return $this->status === JobStatus::Expired ||
               $this->status === JobStatus::Completed;
    }

    public function detailable(): bool
    {
        return $this->content === JobContent::Detailable;
    }

    public function redirectable(): bool
    {
        return $this->link !== null && $this->content === JobContent::Redirectable;
    }

    public function wasCreatedRecently(): bool
    {
        return $this->created_at->gt(Carbon::now()->subMinutes(5));
    }

    public function remuneration(): string
    {
        $remuneration = $this->remuneration;

        $type  = $remuneration->get('type');
        $value = $remuneration->get('value');

        return sprintf(__('app.job.remuneration.' . $type), number_format($value, 2, ',', '.'));
    }
}
