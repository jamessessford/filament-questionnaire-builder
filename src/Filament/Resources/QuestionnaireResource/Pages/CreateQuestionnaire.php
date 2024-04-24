<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource\Pages;

use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

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
