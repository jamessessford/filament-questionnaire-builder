<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompletedQuestionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'questions',
        'answers',
        'questionnaire_id',
        'tenant_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'questions' => 'array',
            'answers' => 'array',
        ];
    }

    public function getTable(): string
    {
        return config('filament-questionnaire-builder.tables.completed-questionnaires.name');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(app()->make(config('filament-questionnaire-builder.tenant_model')), 'tenant_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(app()->make('filament-questionnaire-builder.user_model'));
    }

    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(
            Questionnaire::class,
            config('filament-questionnaire-builder.tables.questionnaires.name') . "_id",
            "id"
        );
    }
}
