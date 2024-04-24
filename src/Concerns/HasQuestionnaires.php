<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\Questionnaire;

trait HasQuestionnaires
{
    public function questionnaires(): HasMany
    {
        return $this->hasMany(Questionnaire::class,
            get_class($this) === config('filament-questionnaire-builder.tenant_model') ? 'tenant_id' : 'user_id',
            'id'
        );
    }
}
