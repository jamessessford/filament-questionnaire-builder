<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources;

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
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\QuestionSetResource\Pages;

class QuestionSetResource extends Resource
{
    protected static ?string $navigationGroup = 'Questionnaires';

    public static function getModel(): string
    {
        return config('filament-questionnaire-builder.models.question-sets');
    }

    public static function getModelLabel(): string
    {
        return 'Question Set';
    }

    public static function getTenantRelationshipName(): string
    {
        return 'questionSets';
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
                        Forms\Components\Builder::make('data')
                            ->label('Questions')
                            ->minItems(1)
                            ->blocks([
                                Forms\Components\Builder\Block::make('text')
                                    ->icon('heroicon-o-pencil')
                                    ->label(function (?array $state): string {
                                        if ($state === null) {
                                            return 'Text Input';
                                        }

                                        return $state['label'] ?? 'Untitled Text Input';
                                    })
                                    ->schema([
                                        self::getFieldNameInput(),
                                        Forms\Components\Checkbox::make('is_required'),
                                        self::getHintInput(),
                                    ]),
                                Forms\Components\Builder\Block::make('dependant_text')
                                    ->icon('heroicon-o-cursor-arrow-rays')
                                    ->label(function (?array $state): string {
                                        if ($state === null) {
                                            return 'Dependant Text';
                                        }

                                        return $state['label'] ?? 'Untitled Dependant Text';
                                    })
                                    ->schema([
                                        self::getFieldNameInput(),
                                        Forms\Components\Select::make('depends_on')
                                            ->reactive()
                                            ->options(function (Livewire $livewire) {
                                                return collect($livewire->data['data'])
                                                    ->filter(function ($block) {
                                                        return in_array($block['type'], ['radio', 'select']);
                                                    })
                                                    ->pluck('data.label', 'data.name');
                                            }),
                                        Forms\Components\Select::make('depends_with')
                                            ->options(function (Forms\Get $get, Livewire $livewire) {
                                                $state = $get('depends_on');
                                                if (!$state) return [];


                                                $options = collect($livewire->data['data'])
                                                    ->filter(function ($block) use ($state) {
                                                        return $block['data']['name'] === $state;
                                                    })
                                                    ->pluck('data.options');

                                                return collect($options[0])->pluck('label', 'value');
                                            }),
                                        self::getHintInput(),
                                    ])
                                    ->hidden(function (Livewire $livewire) {
                                        return collect($livewire->data['data'])
                                            ->filter(function ($block) {
                                                return in_array($block['type'], ['radio', 'select']);
                                            })
                                            ->count() < 1;
                                    }),
                                Forms\Components\Builder\Block::make('select')
                                    ->icon('heroicon-o-chevron-up-down')
                                    ->label(function (?array $state): string {
                                        if ($state === null) {
                                            return 'Select';
                                        }

                                        return $state['label'] ?? 'Untitled Select';
                                    })
                                    ->schema([
                                        self::getFieldNameInput(),
                                        Forms\Components\Repeater::make('options')
                                            ->schema([
                                                self::getKeyValueInput(),
                                                // Forms\Components\Toggle::make('marks_indeterminate')
                                                //     ->columnSpan(3)
                                                //     ->inline(false),
                                                // Forms\Components\Toggle::make('marks_repudiated')
                                                //     ->columnSpan(3)
                                                //     ->inline(false),
                                                // Forms\Components\Toggle::make('marks_urgent')
                                                //     ->columnSpan(3)
                                                //     ->inline(false),
                                            ])
                                            ->columns(15),
                                        Forms\Components\Checkbox::make('is_multiple'),
                                        Forms\Components\Checkbox::make('is_required'),
                                        self::getHintInput(),
                                    ]),
                                Forms\Components\Builder\Block::make('radio')
                                    ->icon('heroicon-o-arrow-down-circle')
                                    ->label(function (?array $state): string {
                                        if ($state === null) {
                                            return 'Radio';
                                        }

                                        return $state['label'] ?? 'Untitled Radio';
                                    })
                                    ->schema([
                                        self::getFieldNameInput(),
                                        Forms\Components\Repeater::make('options')
                                            ->schema([
                                                self::getKeyValueInput(),
                                                // Forms\Components\Toggle::make('marks_indeterminate')
                                                //     ->columnSpan(3)
                                                //     ->inline(false),
                                                // Forms\Components\Toggle::make('marks_repudiated')
                                                //     ->columnSpan(3)
                                                //     ->inline(false),
                                                // Forms\Components\Toggle::make('marks_urgent')
                                                //     ->columnSpan(3)
                                                //     ->inline(false),
                                            ])
                                            ->columns(12),
                                        Forms\Components\Checkbox::make('is_required'),
                                        self::getHintInput(),
                                    ]),
                                Forms\Components\Builder\Block::make('checkbox')
                                    ->icon('heroicon-o-check-circle')
                                    ->label(function (?array $state): string {
                                        if ($state === null) {
                                            return 'Checkbox';
                                        }

                                        return $state['label'] ?? 'Untitled Checkbox';
                                    })
                                    ->schema([
                                        self::getFieldNameInput(),
                                        Forms\Components\Checkbox::make('is_required'),
                                        self::getHintInput(),
                                    ]),
                                Forms\Components\Builder\Block::make('file')
                                    ->icon('heroicon-o-photo')
                                    ->label(function (?array $state): string {
                                        if ($state === null) {
                                            return 'File Upload';
                                        }

                                        return $state['label'] ?? 'Untitled File Upload';
                                    })
                                    ->schema([
                                        self::getFieldNameInput(),
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\Checkbox::make('is_multiple'),
                                                Forms\Components\Checkbox::make('is_required'),
                                            ]),
                                        self::getHintInput(),
                                    ]),
                            ]),
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
            'index' => Pages\ListQuestionSets::route('/'),
            'create' => Pages\CreateQuestionSet::route('/create'),
            'edit' => Pages\EditQuestionSet::route('/{record}/edit'),
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
