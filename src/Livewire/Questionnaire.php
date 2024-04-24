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

    public string $submitAction = '';

    public ?array $data = [];

    public function mount(): void
    {
        if (! $this->submitAction) {
            throw new \Exception('No submit action');
        }

        $this->form->fill();
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
            $questionSet = QuestionSet::where('id', $questionSetId)->sole();
            $steps[] = Forms\Components\Wizard\Step::make($questionSet->title)
                ->schema(
                    $this->constructStep($questionSet->data)
                )
                ->statePath("data.{$questionSet->title}");
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
                    ->required($config['is_required'])
                    ->helperText($config['hint'])
                    ->reactive(),
                'dependant_text' => Forms\Components\TextInput::make($config['name'])
                    ->label($config['label'])
                    ->required(function (Forms\Get $get) use ($config) {
                        if ($config['depends_on']) {
                            return $get($config['depends_on']) == $config['depends_with'];
                        }

                        return false;
                    })
                    ->hidden(function (Forms\Get $get) use ($config) {
                        if ($config['depends_on']) {
                            return $get($config['depends_on']) != $config['depends_with'];
                        }

                        return true;
                    })
                    ->reactive()
                    ->helperText($config['hint']),
                'radio' => Forms\Components\Radio::make($config['name'])
                    ->label($config['label'])
                    ->options($config['options'])
                    ->required($config['is_required'])
                    ->helperText($config['hint'])
                    ->reactive(),
                'select' => Forms\Components\Select::make($config['name'])
                    ->label($config['label'])
                    ->options($config['options'])
                    ->required($config['is_required'])
                    ->helperText($config['hint'])
                    ->reactive(),
                'checkbox' => Forms\Components\Checkbox::make($config['name'])
                    ->label($config['label'])
                    ->required($config['is_required'])
                    ->helperText($config['hint'])
                    ->reactive(),
                'file' => Forms\Components\FileUpload::make($config['name'])
                    ->label($config['label'])
                    ->multiple($config['is_multiple'])
                    ->required($config['is_required'])
                    ->helperText($config['hint'])
                    ->reactive(),
            };
        }, $questionSet);
    }

    public function render()
    {
        return view('filament-questionnaire-builder::questionnaire');
    }
}
