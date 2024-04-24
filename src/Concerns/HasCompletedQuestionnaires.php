<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\CompletedQuestionnaire;

trait HasCompletedQuestionnaires
{
    public function completedQuestionnaires(): HasMany
    {
        return $this->hasMany(CompletedQuestionnaire::class,
            get_class($this) === config('filament-questionnaire-builder.tenant_model') ? 'tenant_id' : 'user_id',
            'id'
        );
    }
}
