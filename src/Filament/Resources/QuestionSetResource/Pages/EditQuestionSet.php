<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource\Pages;

use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestionSet extends EditRecord
{
    protected static string $resource = QuestionSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
