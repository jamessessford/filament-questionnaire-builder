<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource\Pages;

use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompletedQuestionnaires extends ListRecords
{
    protected static string $resource = CompletedQuestionnaireResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
