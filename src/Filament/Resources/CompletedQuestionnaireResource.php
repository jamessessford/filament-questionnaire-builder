<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
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
                Forms\Components\Section::make('Questionnaire')
                    ->schema([
                        Forms\Components\Placeholder::make('title')
                            ->content(fn(Model $record) => $record->questions['title']),
                        Forms\Components\Repeater::make('questions.steps')
                            ->label('')
                            ->deletable(false)
                            ->reorderable(false)
                            ->schema([
                                Forms\Components\Placeholder::make('title')
                                    ->label('Step')
                                    ->content(fn(string $state) => $state),
                                Forms\Components\Repeater::make('step')
                                    ->reorderable(false)
                                    ->deletable(false)
                                    ->label('Questions')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\Placeholder::make('data.label')
                                                    ->label(function(Forms\Get $get) {
                                                        return match($get('type')) {
                                                            'statement' => 'Statement',
                                                            default => 'Question'
                                                        };
                                                    })
                                                    ->content(fn(string $state) => $state),
                                                Forms\Components\Placeholder::make('data.name')
                                                    ->label(function(Forms\Get $get) {
                                                        return match($get('type')) {
                                                            'statement' => 'Body',
                                                            'file' => 'Link',
                                                            default => 'Answer'
                                                        };
                                                    })
                                                    // ->content(fn($state) => $state)
                                                    ->content(function($state, Model $record, Forms\Set $set, Forms\Get $get) {

                                                        if (! $state) {
                                                            return '';
                                                        }

                                                        if ($get('type') === 'statement') {
                                                            return $get('data.value');
                                                        }

                                                        if ($get('type') === 'file') {
                                                            if (array_key_exists($state, $record->answers['data'])) {
                                                                $state = $record->answers['data'][$state];
                                                                if (! is_array($state)) {
                                                                    $newState = <<<html
                                                                    <a href="/storage/{$state}" target="_blank">{$state}</a>
                                                                    html;
                                                                } else {
                                                                    $newState = "";
                                                                    foreach($state as $s) {
                                                                        $newState .= <<<html
                                                                    <a href="/storage/{$s}" target="_blank">{$s}</a>
                                                                    html;
                                                                    }
                                                                }

                                                                return new HtmlString($newState);
                                                            }
                                                        }

                                                        if (array_key_exists($state, $record->answers['data'])) {
                                                            $newState = $record->answers['data'][$state];

                                                            if (is_bool($newState)) {
                                                                $newState = $newState ? 'True' : 'False';
                                                            }

                                                            return $newState;
                                                        }
                                                    })
                                            ])
                                            // ->hidden(fn(Model $record, Forms\Get $get) => ! array_key_exists($get('data.name'), $record->answers['data']))
                                    ])
                                    ->addable(false)
                            ])
                            ->addable(false)
                    ])
                    ->collapsible()
                    ->hidden(fn(Forms\Get $get) => is_null($get('questions')))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('questionnaire.title')
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
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewCompletedQuestionnaire::route('/{record}'),
        ];
    }
}
