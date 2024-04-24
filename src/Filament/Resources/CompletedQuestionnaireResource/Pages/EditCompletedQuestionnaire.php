<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource\Pages;

use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompletedQuestionnaire extends EditRecord
{
    protected static string $resource = CompletedQuestionnaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
