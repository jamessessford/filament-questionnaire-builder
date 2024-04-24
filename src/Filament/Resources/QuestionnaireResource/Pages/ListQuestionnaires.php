<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource;

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
