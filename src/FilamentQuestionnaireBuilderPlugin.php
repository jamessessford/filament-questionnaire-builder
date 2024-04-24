<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use PreferredManagement\FilamentQuestionnaireBuilder\Filament\Resources;

class FilamentQuestionnaireBuilderPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-questionnaire-builder';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                Resources\QuestionSetResource::class,
                Resources\QuestionnaireResource::class,
                Resources\CompletedQuestionnaireResource::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Questionnaires')
                    ->label('Questionnaires')
                    ->icon('heroicon-o-question-mark-circle')
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
