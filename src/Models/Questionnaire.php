<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tags',
        'data',
        'tenant_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'data' => 'array',
        ];
    }

    public function getTable(): string
    {
        return config('filament-questionnaire-builder.tables.questionnaires.name');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(app()->make(config('filament-questionnaire-builder.tenant_model')), 'tenant_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(app()->make('filament-questionnaire-builder.user_model'));
    }

    public function completedQuestionnaires(): HasMany
    {
        return $this->hasMany(
            CompletedQuestionnaire::class,
            config('filament-questionnaire-builder.tables.questionnaires.name') . '_id',
            'id',
        );
    }
}
