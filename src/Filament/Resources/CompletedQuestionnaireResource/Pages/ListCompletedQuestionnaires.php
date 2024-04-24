<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource\Pages;

use Filament\Resources\Pages\ListRecords;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource;

class ListCompletedQuestionnaires extends ListRecords
{
    protected static string $resource = CompletedQuestionnaireResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
