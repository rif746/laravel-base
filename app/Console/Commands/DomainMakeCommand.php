<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class DomainMakeCommand extends Command
{
    protected $signature = 'domain:make
        {type   : Type to generate: model, action, dto, enum, event, listener, notification, policy, query, provider, relationship-provider, export, mapper, scope, mailable}
        {domain : Domain name, e.g. Identity, Account, System}
        {name   : Class name, supports sub-paths e.g. Backup/DeleteBackup}
        {--factory   : Also generate a factory (model only)}
        {--migration : Also generate a migration (model only)}
        {--model=    : Associate the export with a model}';

    protected $description = 'Generate a file directly into the domain structure (app/Domains/)';

    /** @var array<string, string> type → subdirectory */
    protected array $types = [
        'model'                 => 'Models',
        'action'                => 'Actions',
        'dto'                   => 'DTOs',
        'enum'                  => 'Enums',
        'event'                 => 'Events',
        'listener'              => 'Listeners',
        'notification'          => 'Notifications',
        'policy'                => 'Policies',
        'scope'                 => 'Scopes',
        'trait'                 => 'Traits',
        'query'                 => 'Queries',
        'provider'              => 'Providers',
        'relationship-provider' => 'Providers',
        'view-provider' => 'Providers',
        'export'                => 'Exports',
        // Integration layer — files live under Integration/<subDir>/
        'mapper'                => 'Integration/Mappers',
        'mailable'              => 'Mail',
    ];

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $type = strtolower($this->argument('type'));
        $domain = ucfirst($this->argument('domain'));
        $name = $this->argument('name'); // may contain sub-path, e.g. Backup/DeleteBackup

        if (! isset($this->types[$type])) {
            $this->components->error("Unknown type [{$type}]. Supported: ".implode(', ', array_keys($this->types)));

            return self::FAILURE;
        }

        $subDir = $this->types[$type];
        $className = class_basename(str_replace('/', '\\', $name));
        $subPath = str_contains($name, '/') ? dirname($name) : null;

        // ── Integration / Mapper special handling ─────────────────────────────
        // Automatically append the 'DataMapper' suffix when the developer omits it,
        // keeping the class name consistent with the DataPayloadMapper contract.
        if ($type === 'mapper' && ! str_ends_with($className, 'DataMapper')) {
            $className .= 'DataMapper';
        }

        // The $subDir for 'mapper' already encodes the full Integration/Mappers
        // nested path, so we must not double-nest an additional subPath beneath it.
        // Extra sub-path segments are intentionally ignored for the mapper type.
        $relativeDir = ($type === 'mapper')
            ? "Domains/{$domain}/{$subDir}"
            : "Domains/{$domain}/{$subDir}".($subPath ? "/{$subPath}" : '');
        // ─────────────────────────────────────────────────────────────────────

        $namespace = 'App\\'.str_replace('/', '\\', $relativeDir);
        $path = app_path("{$relativeDir}/{$className}.php");

        if ($this->files->exists($path)) {
            $this->components->error("File already exists: [{$path}]");

            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $this->buildStub($type, $namespace, $className, $domain));

        $this->components->info("File [{$path}] created successfully.");

        if ($type === 'model' && $this->option('factory')) {
            $this->createFactory($domain, $className, $namespace);
        }

        if ($type === 'model' && $this->option('migration')) {
            $table = Str::snake(Str::pluralStudly($className));
            $this->call('make:migration', ['name' => "create_{$table}_table"]);
        }

        return self::SUCCESS;
    }

    // ─── Stubs ────────────────────────────────────────────────────────────────

    protected function buildStub(string $type, string $namespace, string $name, string $domain): string
    {
        $stubPath = app_path("Console/stubs/domain-make/{$type}.stub");

        if (! $this->files->exists($stubPath)) {
            $this->components->error("Stub file not found: [{$stubPath}]");

            return '';
        }

        $stub = $this->files->get($stubPath);

        $replacements = [
            '{{ namespace }}' => $namespace,
            '{{ class }}' => $name,
        ];

        if ($type === 'model') {
            $replacements['{{ factoryImport }}'] = $this->option('factory')
                ? "\nuse Database\\Factories\\{$domain}\\{$name}Factory;\nuse Illuminate\\Database\\Eloquent\\Factories\\Factory;"
                : '';
            $replacements['{{ factoryMethod }}'] = $this->option('factory')
                ? "\n    protected static function newFactory(): Factory\n    {\n        return {$name}Factory::new();\n    }"
                : '';
        }

        if ($type === 'export') {
            $modelOption = $this->option('model');
            $modelImport = '';
            $queryBody = '        // return YourModel::query();';

            if ($modelOption) {
                $modelData = $this->resolveModel($modelOption, $domain);
                $modelImport = "use {$modelData['full']};\n";
                $modelClass = $modelData['class'];
                $queryBody = <<<PHP
        return {$modelClass}::query()
            // ->with('profile') // CRITICAL: Eager load any relations used in map()
            // ->when(isset(\$this->filters['status']), fn(Builder \$q) => \$q->where('status', \$this->filters['status']))
            ;
PHP;
            }

            $replacements['{{ modelImport }}'] = $modelImport;
            $replacements['{{ queryBody }}'] = $queryBody;
        }

        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

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

    protected function createFactory(string $domain, string $name, string $modelNamespace): void
    {
        $factoryNamespace = "Database\\Factories\\{$domain}";
        $factoryPath = database_path("factories/{$domain}/{$name}Factory.php");

        $this->files->ensureDirectoryExists(dirname($factoryPath));

        if ($this->files->exists($factoryPath)) {
            $this->components->warn("Factory already exists: [{$factoryPath}]");

            return;
        }

        $stubPath = app_path('Console/stubs/domain-make/factory.stub');
        $stub = $this->files->exists($stubPath) ? $this->files->get($stubPath) : '';

        $stub = str_replace(
            ['{{ factoryNamespace }}', '{{ modelNamespace }}', '{{ class }}'],
            [$factoryNamespace, $modelNamespace, $name],
            $stub
        );

        $this->files->put($factoryPath, $stub);

        $this->components->info("Factory [database/factories/{$domain}/{$name}Factory.php] created successfully.");
    }
}
