<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Component as Livewire;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource\Pages;

class CompletedQuestionnaireResource extends Resource
{
    protected static ?string $navigationGroup = 'Questionnaires';

    public static function getModel(): string
    {
        return config('filament-questionnaire-builder.models.completed-questionnaires');
    }

    public static function getModelLabel(): string
    {
        return 'Completed Questionnaire';
    }

    public static function getTenantRelationshipName(): string
    {
        return 'completedQuestionnaires';
    }

    public static function getTenantOwnershipRelationshipName(): string
    {
        return 'tenant';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompletedQuestionnaires::route('/'),
            'edit' => Pages\EditCompletedQuestionnaire::route('/{record}/edit'),
        ];
    }
}
