<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PageMakeCommand extends Command
{
    protected $signature = 'domain:make-page
        {domain : Domain name, e.g. Identity, Account, System}
        {capability : Capability name, e.g. Users, Roles, Profile}
        {name : Page name, e.g. index, detail}
        {--modal : Generate a Livewire modal component}';

    protected $description = 'Generate a blade view inside resources/views/pages/{domain}/{capability}';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $domain = strtolower($this->argument('domain'));
        $capability = strtolower($this->argument('capability'));
        $name = $this->argument('name');
        $isModal = $this->option('modal');

        $directory = resource_path("views/pages/{$domain}/{$capability}");

        if ($isModal) {
            return $this->handleModal($directory, $name);
        }

        $path = "{$directory}/{$name}.blade.php";

        if ($this->files->exists($path)) {
            $this->components->error("File already exists: [{$path}]");

            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists($directory);

        $stubPath = app_path('Console/stubs/domain-make/page.stub');
        $stub = $this->files->get($stubPath);

        $this->files->put($path, $stub);

        $this->components->info("Page created: resources/views/pages/{$domain}/{$capability}/{$name}.blade.php");

        return self::SUCCESS;
    }

    protected function handleModal(string $directory, string $name): int
    {
        $modalName = Str::kebab($name);
        if (! str_ends_with($modalName, '-modal')) {
            $modalName .= '-modal';
        }

        $modalDir = "{$directory}/⚡{$modalName}";

        if ($this->files->exists($modalDir)) {
            $this->components->error("Modal directory already exists: [{$modalDir}]");

            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists($modalDir);

        $modelVariable = Str::camel(Str::before($modalName, '-modal'));

        // Class
        $classStub = $this->files->get(app_path('Console/stubs/domain-make/page-modal-class.stub'));
        $classContent = str_replace('{{ modelVariable }}', $modelVariable, $classStub);
        $this->files->put("{$modalDir}/{$modalName}.php", $classContent);

        // View
        $viewStub = $this->files->get(app_path('Console/stubs/domain-make/page-modal-view.stub'));
        $viewContent = str_replace('{{ modalId }}', $modalName, $viewStub);
        $this->files->put("{$modalDir}/{$modalName}.blade.php", $viewContent);

        $this->components->info('Livewire Modal created in: resources/views/pages/...'."/⚡{$modalName}");

        return self::SUCCESS;
    }
}
