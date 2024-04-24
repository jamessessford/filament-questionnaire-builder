<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource\Pages;

use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

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
