<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class DomainMakeCommand extends Command
{
    protected $signature = 'domain:make
        {type   : Type to generate: model, action, dto, enum, event, listener, notification, policy, query, provider, export, mapper}
        {domain : Domain name, e.g. Identity, Account, System}
        {name   : Class name, supports sub-paths e.g. Backup/DeleteBackup}
        {--factory   : Also generate a factory (model only)}
        {--migration : Also generate a migration (model only)}
        {--model=    : Associate the export with a model}';

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
        'query' => 'Queries',
        'provider' => 'Providers',
        'export' => 'Exports',
        // Integration layer — files live under Integration/<subDir>/
        'mapper' => 'Integration/Mappers',
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

        if (!isset($this->types[$type])) {
            $this->components->error("Unknown type [{$type}]. Supported: " . implode(', ', array_keys($this->types)));

            return self::FAILURE;
        }

        $subDir = $this->types[$type];
        $className = class_basename(str_replace('/', '\\', $name));
        $subPath = str_contains($name, '/') ? dirname($name) : null;

        // ── Integration / Mapper special handling ─────────────────────────────
        // Automatically append the 'DataMapper' suffix when the developer omits it,
        // keeping the class name consistent with the DataPayloadMapper contract.
        if ($type === 'mapper' && !str_ends_with($className, 'DataMapper')) {
            $className .= 'DataMapper';
        }

        // The $subDir for 'mapper' already encodes the full Integration/Mappers
        // nested path, so we must not double-nest an additional subPath beneath it.
        // Extra sub-path segments are intentionally ignored for the mapper type.
        $relativeDir = ($type === 'mapper')
            ? "Domains/{$domain}/{$subDir}"
            : "Domains/{$domain}/{$subDir}" . ($subPath ? "/{$subPath}" : '');
        // ─────────────────────────────────────────────────────────────────────

        $namespace = 'App\\' . str_replace('/', '\\', $relativeDir);
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
            'query' => $this->queryStub($namespace, $name),
            'provider' => $this->providerStub($namespace, $name),
            'export' => $this->exportStub($namespace, $name, $domain),
            'mapper' => $this->mapperStub($namespace, $name, $domain),
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

    private function providerStub(string $namespace, string $name): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Support\ServiceProvider;

class {$name} extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
PHP;
    }

    protected function exportStub(string $namespace, string $name, string $domain): string
    {
        $modelOption = $this->option('model');
        $modelImport = '';
        $modelClass = 'YourModel';

        if ($modelOption) {
            $modelData = $this->resolveModel($modelOption, $domain);
            $modelImport = "use {$modelData['full']};\n";
            $modelClass = $modelData['class'];
        }

        $body = <<<PHP
    use Exportable;

    // Accept UI filters directly into the Export class
    public function __construct(
        private array \$filters = []
    ) {}

    /**
     * We use FromQuery instead of FromCollection to allow chunking.
     */
    public function query(): Builder
    {
PHP;

        if ($modelOption) {
            $body .= <<<PHP

        return {$modelClass}::query()
            // ->with('profile') // CRITICAL: Eager load any relations used in map()
            // ->when(isset(\$this->filters['status']), fn(Builder \$q) => \$q->where('status', \$this->filters['status']))
            ;
    }
PHP;
        } else {
            $body .= <<<PHP

        // return {$modelClass}::query();
    }
PHP;
        }

        $body .= <<<PHP


    public function headings(): array
    {
        return [
            'ID',
            'Created At',
            'Amount', // Example column for currency
        ];
    }

    /**
     * @param mixed \$row
     */
    public function map(\$row): array
    {
        return [
            \$row->id,
            // To format as a true Excel date, pass a native PHP DateTime object or an Excel timestamp
            // \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\$row->created_at),
            \$row->created_at?->format('Y-m-d H:i'),
            // \$row->amount,
        ];
    }

    /**
     * Apply Excel formatting to specific columns (Dates, Currency, Percentages).
     */
    public function columnFormats(): array
    {
        return [
            // Example: Format Column B as a true Date
            // 'B' => NumberFormat::FORMAT_DATE_YYYYMMDD,

            // Example: Format Column C as Currency
            // 'C' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    /**
     * Apply visual styling to specific rows or columns.
     */
    public function styles(Worksheet \$sheet)
    {
        return [
            // Make the first row (the headings) bold
            1 => ['font' => ['bold' => true]],
        ];
    }
PHP;

        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
{$modelImport}
class {$name} implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnFormatting
{
{$body}
}
PHP;
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
            $full = "App\\Domains\\{$domain}\\Models\\" . ucfirst($modelOption);
            $class = ucfirst($modelOption);
        }

        return [
            'full' => $full,
            'class' => $class,
        ];
    }

    protected function mapperStub(string $namespace, string $name, string $domain): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Model;
use App\Domains\System\Support\Integration\DataPayloadMapper;

class {$name} implements DataPayloadMapper
{
    public function __construct()
    {
        // Inject required Domain and Cross-Domain Actions via constructor composition
    }

    public function getLookupKey(): string
    {
        // Return the unique string key used to identify existing records (e.g., 'email')
        return 'id';
    }

    public function transform(array \$rawData): array
    {
        // Normalize incoming data array formats into an internal domain-safe layout
        return \$rawData;
    }

    public function updateOrCreateDomainState(array \$payload, ?Model \$existingModel = null): void
    {
        // Allocate mapped payloads into strict DTOs and fire internal/external Domain Actions
    }
}
PHP;
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
PHP
        );

        $this->components->info("Factory [database/factories/{$domain}/{$name}Factory.php] created successfully.");
    }
}
