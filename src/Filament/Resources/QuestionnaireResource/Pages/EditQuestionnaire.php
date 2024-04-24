<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource;

class EditQuestionnaire extends EditRecord
{
    protected static string $resource = QuestionnaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
