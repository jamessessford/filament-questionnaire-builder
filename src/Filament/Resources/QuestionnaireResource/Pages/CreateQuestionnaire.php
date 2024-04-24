<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource;

class CreateQuestionnaire extends CreateRecord
{
    protected static string $resource = QuestionnaireResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (config('filament-questionnaire-builder.user-associations.questionnaires')) {
            $data['user_id'] = request()->user()->id;
        }

        return $data;
    }
}
