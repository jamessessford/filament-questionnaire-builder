<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\QuestionSet;

trait HasQuestionSets
{
    public function questionSets(): HasMany
    {
        return $this->hasMany(
            QuestionSet::class,
            get_class($this) === config('filament-questionnaire-builder.tenant_model') ? 'tenant_id' : 'user_id',
            'id'
        );
    }
}
