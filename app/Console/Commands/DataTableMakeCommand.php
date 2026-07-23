<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Yajra\DataTables\Generators\DataTablesMakeCommand;

class DataTableMakeCommand extends DataTablesMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:datatable
                            {name   : The name of the DataTable.}
                            {domain : The domain name.}
                            {--model= : The name of the model to be used.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DataTable service class in a domain.';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     */
    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        $domain = ucfirst($this->argument('domain'));
        $className = class_basename(str_replace('/', '\\', $this->getNameInput()));

        $modelOption = $this->option('model') ?? Str::replaceLast('DataTable', '', $className);
        $modelData = $this->resolveModel($modelOption, $domain);

        $modelClass = $modelData['class'];
        $modelVariable = Str::camel($modelClass);
        $tableId = Str::kebab($modelClass).'-table';

        $replacements = [
            '{{ namespace }}' => $this->getNamespace($name),
            '{{ class }}' => $className,
            '{{ modelImport }}' => "use {$modelData['full']};\n",
            '{{ model }}' => $modelClass,
            '{{ modelVariable }}' => $modelVariable,
            '{{ tableId }}' => $tableId,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    /**
     * Resolve model data.
     */
    protected function resolveModel(string $modelOption, string $domain): array
    {
        if (Str::contains($modelOption, ['\\', '/'])) {
            if (Str::startsWith(str_replace('/', '\\', $modelOption), 'App\\')) {
                $full = str_replace('/', '\\', $modelOption);
                $class = class_basename($full);
            } else {
                $parts = explode('/', str_replace('\\', '/', $modelOption));
                $targetDomain = ucfirst($parts[0]);
                $targetName = implode('\\', array_map('ucfirst', array_slice($parts, 1)));
                $full = "App\\Domains\\{$targetDomain}\\Models\\{$targetName}";
                $class = class_basename($full);
            }
        } else {
            $full = "App\\Domains\\{$domain}\\Models\\".ucfirst($modelOption);
            $class = ucfirst($modelOption);
        }

        return [
            'full' => $full,
            'class' => $class,
        ];
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel->basePath().'/app/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        $domain = ucfirst($this->argument('domain'));

        return $rootNamespace."\\Http\\DataTables\\{$domain}";
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return app_path('Console/stubs/domain-datatable/datatable.stub');
    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $status = GeneratorCommand::handle();

        if ($status !== false) {
            $this->createView();
        }

        return $status;
    }

    protected function createView(): void
    {
        $domain = ucfirst($this->argument('domain'));
        $className = class_basename(str_replace('/', '\\', $this->getNameInput()));
        $capability = Str::replaceLast('DataTable', '', $className);

        $directory = resource_path('views/pages/'.Str::lower($domain).'/'.Str::lower(Str::plural($capability)));
        $path = "{$directory}/index.blade.php";

        if ($this->files->exists($path)) {
            $this->components->warn("View already exists: [{$path}]");

            return;
        }

        $this->files->ensureDirectoryExists($directory);

        $stub = $this->files->get(app_path('Console/stubs/domain-datatable/datatable-view.stub'));

        $modelOption = $this->option('model') ?? $capability;
        $modelData = $this->resolveModel($modelOption, $domain);

        $replacements = [
            '{{ domain }}' => $domain,
            '{{ domainLower }}' => Str::lower($domain),
            '{{ domainDot }}' => Str::lower($domain),
            '{{ capabilityDot }}' => Str::lower(Str::plural($capability)),
            '{{ model }}' => $modelData['class'],
            '{{ modelFull }}' => $modelData['full'],
            '{{ modelVariable }}' => Str::camel($modelData['class']),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $this->files->put($path, $content);

        $this->components->info('View created: resources/views/pages/'.Str::lower($domain).'/'.Str::lower(Str::plural($capability)).'/index.blade.php');
    }
}
