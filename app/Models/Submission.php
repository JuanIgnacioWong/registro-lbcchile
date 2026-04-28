<?php

namespace App\Models;

use Database\Factories\SubmissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    /** @use HasFactory<SubmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'season_id',
        'division_id',
        'club_id',
        'responsible_name',
        'phone',
        'email',
        'payment_status',
        'active_version',
        'max_allowed_submissions',
    ];

    protected function casts(): array
    {
        return [
            'active_version' => 'integer',
            'max_allowed_submissions' => 'integer',
        ];
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(SubmissionVersion::class);
    }

    public function submittedVersionsCount(): int
    {
        return $this->versions()->count();
    }

    public function canReceiveNewVersion(): bool
    {
        return $this->submittedVersionsCount() < $this->max_allowed_submissions;
    }
}
