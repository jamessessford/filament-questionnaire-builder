<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource;

class ListQuestionSets extends ListRecords
{
    protected static string $resource = QuestionSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
