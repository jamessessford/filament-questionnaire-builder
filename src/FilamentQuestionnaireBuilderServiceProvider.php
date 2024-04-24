<?php

namespace PreferredManagement\FilamentQuestionnaireBuilder;

use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use PreferredManagement\FilamentQuestionnaireBuilder\Commands\FilamentQuestionnaireBuilderCommand;
use PreferredManagement\FilamentQuestionnaireBuilder\Testing\TestsFilamentQuestionnaireBuilder;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentQuestionnaireBuilderServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-questionnaire-builder';

    public static string $viewNamespace = 'filament-questionnaire-builder';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('preferredmanagement/filament-questionnaire-builder');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-questionnaire-builder/{$file->getFilename()}"),
                ], 'filament-questionnaire-builder-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentQuestionnaireBuilder());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'preferredmanagement/filament-questionnaire-builder';
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentQuestionnaireBuilderCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_question_sets_table',
            'create_questionnaires_table',
            'create_completed_questionnaires_table',
        ];
    }
}
