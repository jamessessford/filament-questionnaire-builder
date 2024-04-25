<?php

//  Have a look if we can do video as well as img/pdf etc;

namespace PreferredManagement\FilamentQuestionnaireBuilder\Livewire;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\Questionnaire as QuestionnaireModel;
use PreferredManagement\FilamentQuestionnaireBuilder\Models\QuestionSet;

abstract class Questionnaire extends Component implements HasForms
{
    use InteractsWithForms;

    public QuestionnaireModel $questionnaire;

    public string $submitAction = 'save';

    public ?array $data = [];

    public array $questions = [];

    public function mount(): void
    {
        $this->questions = [
            'title' => $this->questionnaire->title,
            'steps' => [],
        ];

        $this->form->fill();
    }

    public function save(): void
    {
        $answers = $this->form->getState();

        $this->questionnaire->completedQuestionnaires()->create([
            'user_id' => request()->user()?->id,
            'tenant_id' => $this->questionnaire->tenant_id,
            'questions' => $this->questions,
            'answers' => $answers,
        ]);

        $this->redirect("/questionnaire/{$this->questionnaire->id}");
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('form')
                    ->heading($this->questionnaire->title)
                    ->schema([
                        $this->constructWizard(),
                    ]),
            ]);
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
                )
                ->statePath('data');
        }

        $wizard = Forms\Components\Wizard::make()
            ->steps($steps)
            ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="button"
                        size="sm"
                        wire:click="{$this->submitAction}"
                    >
                        Submit
                    </x-filament::button>
                BLADE)));

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
                    ->required(fn (Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn (Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'radio' => Forms\Components\Radio::make($config['name'])
                    ->label($config['label'])
                    ->options($config['options'])
                    ->required(fn (Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn (Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'select' => Forms\Components\Select::make($config['name'])
                    ->label($config['label'])
                    ->options($config['options'])
                    ->required(fn (Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn (Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'checkbox' => Forms\Components\Checkbox::make($config['name'])
                    ->label($config['label'])
                    ->required(fn (Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn (Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'file' => Forms\Components\FileUpload::make($config['name'])
                    ->label($config['label'])
                    ->multiple($config['is_multiple'])
                    ->required(fn (Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn (Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->helperText($config['hint'])
                    ->reactive(),
                'statement' => Forms\Components\Placeholder::make($config['name'])
                    ->label($config['label'])
                    ->hidden(fn (Forms\Get $get) => $this->potentiallyHidden($config, $get))
                    ->content($config['value']),
                'toggle' => Forms\Components\Toggle::make($config['name'])
                    ->label($config['label'])
                    ->accepted(fn (Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->required(fn (Forms\Get $get) => $this->potentiallyRequired($config, $get))
                    ->hidden(fn (Forms\Get $get) => $this->potentiallyHidden($config, $get))
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

    public function render()
    {
        return view('filament-questionnaire-builder::questionnaire');
    }
}
