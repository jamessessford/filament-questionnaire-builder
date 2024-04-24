<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionSet extends Model
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
        return config('filament-questionnaire-builder.tables.question-sets.name');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(app()->make(config('filament-questionnaire-builder.tenant_model')), 'tenant_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(app()->make('filament-questionnaire-builder.user_model'));
    }
}
