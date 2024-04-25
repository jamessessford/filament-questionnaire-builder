<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource\Pages;

use Filament\Actions;
use Filament\Forms;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources\CompletedQuestionnaireResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\CompletedQuestionnaire;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\Questionnaire;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\QuestionSet;

class ViewCompletedQuestionnaire extends ViewRecord
{
    protected static string $resource = CompletedQuestionnaireResource::class;

    public Questionnaire $questionnaire;

    public array $questions = [];

    public function booted()
    {
        $this->questionnaire = $this->record->questionnaire;

        $this->questions = [
            'title' => $this->questionnaire->title,
            'steps' => [],
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('edit')
                ->label('Update Answers')
                ->slideOver()
                ->fillForm(function() {
                    return $this->record->answers['data'];
                })
                ->form([
                    Forms\Components\Section::make('form')
                        ->heading($this->questionnaire->title)
                        ->schema([
                            $this->constructWizard(),
                        ]),
                ])
                ->action(function(array $data) {
                    $answers = $data;

                    $this->record->update([
                        'questions' => $this->questions,
                        'answers' => ['data' => $answers],
                    ]);

                    $this->redirect(CompletedQuestionnaireResource::getUrl('view', ['record' => $this->record]));
                })
        ];
    }

    protected function constructWizard(): Forms\Components\Wizard
    {
        $steps = [];
        foreach ($this->questionnaire->data as $questionSetId) {
            $questionSet = QuestionSet::where('id', $questionSetId['question_set'])->sole();

            $this->questions['steps'][$questionSetId['question_set']] = ['title' => $questionSet->title, 'step' => $questionSet->data];

            $steps[] = Forms\Components\Wizard\Step::make($questionSet->title)
                ->schema(
                    $this->constructStep($questionSet->data)
                );
        }

        $wizard = Forms\Components\Wizard::make()
            ->steps($steps);

        return $wizard;
    }

    protected function constructStep($questionSet): array
    {
        return array_map(function (array $field) {
            $config = $field['data'];
            if (array_key_exists('options', $config)) {
                $config['options'] = collect($config['options'])->pluck('label', 'value')->toArray();
            }

            return match ($field['type']) {
                'text' => Forms\Components\TextInput::make($config['name'])
                    ->label($config['label'])
                    ->required(fn(Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn(Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'radio' => Forms\Components\Radio::make($config['name'])
                    ->label($config['label'])
                    ->options($config['options'])
                    ->required(fn(Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn(Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'select' => Forms\Components\Select::make($config['name'])
                    ->label($config['label'])
                    ->options($config['options'])
                    ->required(fn(Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn(Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'checkbox' => Forms\Components\Checkbox::make($config['name'])
                    ->label($config['label'])
                    ->required(fn(Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn(Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'file' => Forms\Components\FileUpload::make($config['name'])
                    ->label($config['label'])
                    ->multiple($config['is_multiple'])
                    ->required(fn(Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn(Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'statement' => Forms\Components\Placeholder::make($config['name'])
                    ->label($config['label'])
                    ->hidden(fn(Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->content($config['value']),
                'toggle' => Forms\Components\Toggle::make($config['name'])
                    ->label($config['label'])
                    ->accepted(fn(Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->required(fn(Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn(Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
            };
        }, $questionSet);
    }

    protected function potentiallyHidden(array $config, Forms\Get $get): bool
    {
        if (! array_key_exists('has_dependencies', $config) || ! $config['has_dependencies']) {
            return false;
        }

        if ($get($config['depends_on']) !== $config['depends_with']) {
            return true;
        }

        return false;
    }

    protected function potentiallyRequired(array $config, Forms\Get $get): bool
    {
        if (! array_key_exists('has_dependencies', $config) || ! $config['has_dependencies']) {
            return $config['is_required'];
        }

        if ($get($config['depends_on']) === $config['depends_with']) {
            return $config['is_required'];
        }

        return false;
    }
}
