<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource\Pages;

use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuestionnaires extends ListRecords
{
    protected static string $resource = QuestionnaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
