<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource;

class CreateQuestionSet extends CreateRecord
{
    protected static string $resource = QuestionSetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (config('filament-questionnaire-builder.user-associations.question-sets')) {
            $data['user_id'] = request()->user()->id;
        }

        return $data;
    }
}
