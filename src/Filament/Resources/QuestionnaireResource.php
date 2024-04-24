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
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionnaireResource\Pages;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\QuestionSet;

class QuestionnaireResource extends Resource
{
    protected static ?string $navigationGroup = 'Questionnaires';

    public static function getModel(): string
    {
        return config('filament-questionnaire-builder.models.questionnaires');
    }

    public static function getModelLabel(): string
    {
        return 'Questionnaire';
    }

    public static function getTenantRelationshipName(): string
    {
        return 'questionnaires';
    }

    public static function getTenantOwnershipRelationshipName(): string
    {
        return 'tenant';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('data')
                            ->multiple()
                            ->label('Question Sets')
                            ->options(function() {
                                return QuestionSet::query()
                                    ->where(function(Builder $query) {
                                        $query
                                            ->where('user_id', request()->user()->id)
                                            ->orWhere('tenant_id', Filament::getTenant()->id);
                                    })
                                    ->get()
                                    ->pluck('title', 'id');
                            })
                            ->required()
                    ])
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
            'index' => Pages\ListQuestionnaires::route('/'),
            'create' => Pages\CreateQuestionnaire::route('/create'),
            'edit' => Pages\EditQuestionnaire::route('/{record}/edit'),
        ];
    }

    public static function getFieldNameInput(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make()
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->hint('This is displayed on the form')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $name = preg_replace(
                            '/[^A-Za-z0-9\-]/',
                            '',
                            Str::of($state)
                                ->lower()
                                ->kebab()
                        );

                        $set('name', $name);
                    }),
                Forms\Components\TextInput::make('name')
                    ->hint('This is how the field is labelled internally')
                    ->required(),
            ]);
    }

    public static function getKeyValueInput(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make()
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $name = preg_replace(
                            '/[^A-Za-z0-9\-]/',
                            '',
                            Str::of($state)
                                ->lower()
                                ->kebab()
                        );

                        $set('value', $name);
                    }),
                Forms\Components\TextInput::make('value')
                    ->required(),
            ]);
        // ->columnSpan(6);
    }

    public static function getHintInput(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make()
            ->schema([
                Forms\Components\Checkbox::make('has_hint')
                    ->reactive(),
                Forms\Components\TextInput::make('hint')
                    ->visible(fn (Forms\Get $get) => $get('has_hint') == true)
                    ->columnSpanFull()
            ]);
    }
}
