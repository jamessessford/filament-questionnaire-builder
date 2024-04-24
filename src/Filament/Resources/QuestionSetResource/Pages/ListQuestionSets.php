<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource\Pages;

use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
