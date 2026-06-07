<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class DomainMakeCommand extends Command
{
    protected $signature = 'domain:make
        {type   : Type to generate: model, action, dto, enum, event, listener, notification, policy, datatable, query}
        {domain : Domain name, e.g. Identity, Account, System}
        {name   : Class name, supports sub-paths e.g. Backup/DeleteBackup}
        {--factory   : Also generate a factory (model only)}
        {--migration : Also generate a migration (model only)}';

    protected $description = 'Generate a file directly into the domain structure (app/Domains/)';

    /** @var array<string, string> type → subdirectory */
    protected array $types = [
        'model' => 'Models',
        'action' => 'Actions',
        'dto' => 'DTOs',
        'enum' => 'Enums',
        'event' => 'Events',
        'listener' => 'Listeners',
        'notification' => 'Notifications',
        'policy' => 'Policies',
        'trait' => 'Traits',
        'datatable' => 'DataTables',
        'query' => 'Queries',
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

        $relativeDir = "Domains/{$domain}/{$subDir}".($subPath ? "/{$subPath}" : '');
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
        return match ($type) {
            'model' => $this->modelStub($namespace, $name, $domain),
            'action' => $this->actionStub($namespace, $name),
            'dto' => $this->dtoStub($namespace, $name),
            'enum' => $this->enumStub($namespace, $name),
            'trait' => $this->traitStub($namespace, $name),
            'event' => $this->eventStub($namespace, $name),
            'listener' => $this->listenerStub($namespace, $name),
            'notification' => $this->notificationStub($namespace, $name),
            'policy' => $this->policyStub($namespace, $name),
            'datatable' => $this->datatableStub($namespace, $name),
            'query' => $this->queryStub($namespace, $name),
            default => '',
        };
    }

    protected function modelStub(string $namespace, string $name, string $domain): string
    {
        $factoryImport = $this->option('factory')
            ? "\nuse Database\\Factories\\{$domain}\\{$name}Factory;\nuse Illuminate\\Database\\Eloquent\\Factories\\Factory;"
            : '';
        $factoryMethod = $this->option('factory')
            ? "\n    protected static function newFactory(): Factory\n    {\n        return {$name}Factory::new();\n    }"
            : '';

        return <<<PHP
<?php

namespace {$namespace};
{$factoryImport}
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    use HasFactory;
{$factoryMethod}
}
PHP;
    }

    protected function actionStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

class {$name}
{
    public function execute(): void
    {
        //
    }
}
PHP;
    }

    protected function dtoStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

readonly class {$name}
{
    public function __construct(
        //
    ) {}
}
PHP;
    }

    protected function enumStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

enum {$name}: string
{
    //
}
PHP;
    }

    protected function eventStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class {$name}
{

    use Dispatchable, SerializesModels;
    public function __construct(
        //
    ) {}
}
PHP;
    }

    protected function listenerStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

class {$name}
{
    public function handle(object \$event): void
    {
        //
    }
}
PHP;
    }

    protected function notificationStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class {$name} extends Notification
{
    use Queueable;

    public function via(object \$notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object \$notifiable): MailMessage
    {
        return (new MailMessage)->line('');
    }

    public function toArray(object \$notifiable): array
    {
        return [];
    }
}
PHP;
    }

    protected function policyStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use App\Domains\Identity\Models\User;

class {$name}
{
    public function viewAny(User \$user): bool
    {
        return false;
    }

    public function view(User \$user, mixed \$model): bool
    {
        return false;
    }

    public function create(User \$user): bool
    {
        return false;
    }

    public function update(User \$user, mixed \$model): bool
    {
        return false;
    }

    public function delete(User \$user, mixed \$model): bool
    {
        return false;
    }
}
PHP;
    }

    protected function datatableStub(string $namespace, string $name): string
    {
        $tableId = Str::kebab(Str::singular($name));

        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class {$name} extends DataTable
{
    public function dataTable(QueryBuilder \$query): EloquentDataTable
    {
        return (new EloquentDataTable(\$query))
            ->addIndexColumn();
    }

    public function query(): QueryBuilder
    {
        //
    }

    public function html(): HtmlBuilder
    {
        return \$this->builder()
            ->setTableId('{$tableId}-table')
            ->columns(\$this->getColumns())
            ->minifiedAjax()
            ->orderBy(-1);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return '{$name}_' . date('YmdHis');
    }
}
PHP;
    }

    // ─── Factory ──────────────────────────────────────────────────────────────

    protected function createFactory(string $domain, string $name, string $modelNamespace): void
    {
        $factoryNamespace = "Database\\Factories\\{$domain}";
        $factoryPath = database_path("factories/{$domain}/{$name}Factory.php");

        $this->files->ensureDirectoryExists(dirname($factoryPath));

        if ($this->files->exists($factoryPath)) {
            $this->components->warn("Factory already exists: [{$factoryPath}]");

            return;
        }

        $this->files->put($factoryPath, <<<PHP
<?php

namespace {$factoryNamespace};

use {$modelNamespace}\\{$name};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<{$name}>
 */
class {$name}Factory extends Factory
{
    protected \$model = {$name}::class;

    public function definition(): array
    {
        return [];
    }
}
PHP);

        $this->components->info("Factory [database/factories/{$domain}/{$name}Factory.php] created successfully.");
    }

    protected function queryStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Collection;

class {$name}
{
    public function fetch(): Collection
    {
        return new Collection();
    }
}
PHP;
    }

    private function traitStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

trait {$name}
{
    //
}
PHP;
    }
}
