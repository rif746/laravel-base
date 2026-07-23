<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DomainNewCommand extends Command
{
    protected $signature = 'domain:new
        {domain : Domain name, e.g. Order, Payment, Billing}';

    protected $description = 'Scaffold a new domain with a service provider and a relationship provider';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $domain = ucfirst($this->argument('domain'));
        $namespace = "App\\Domains\\{$domain}\\Providers";
        $providerDir = app_path("Domains/{$domain}/Providers");

        if ($this->files->isDirectory($providerDir) && count($this->files->files($providerDir)) > 0) {
            $this->components->error(
                "Domain [{$domain}] already has providers. ".
                'Use [domain:make provider] to add individual providers.'
            );

            return self::FAILURE;
        }

        $this->files->ensureDirectoryExists($providerDir);

        $domainProviderClass = "{$domain}ServiceProvider";

        if (! $this->createDomainProvider($providerDir, $namespace, $domainProviderClass)) {
            return self::FAILURE;
        }

        if (! $this->createRelationshipProvider($providerDir, $namespace)) {
            return self::FAILURE;
        }

        $this->registerInBootstrap($domain, $namespace, $domainProviderClass);

        $this->components->twoColumnDetail(
            "Domain <fg=green;options=bold>{$domain}</> scaffolded",
            '<fg=green;options=bold>DONE</>'
        );

        return self::SUCCESS;
    }

    // ─── Generators ──────────────────────────────────────────────────────────

    protected function createDomainProvider(string $dir, string $namespace, string $class): bool
    {
        $path = "{$dir}/{$class}.php";
        $content = $this->buildFromStub('domain-provider', $namespace, $class);

        if ($content === '') {
            return false;
        }

        $this->files->put($path, $content);
        $this->components->info("Provider [{$path}] created successfully.");

        return true;
    }

    protected function createRelationshipProvider(string $dir, string $namespace): bool
    {
        $path = "{$dir}/RelationshipServiceProvider.php";
        $content = $this->buildFromStub('relationship-provider', $namespace, 'RelationshipServiceProvider');

        if ($content === '') {
            return false;
        }

        $this->files->put($path, $content);
        $this->components->info("Provider [{$path}] created successfully.");

        return true;
    }

    // ─── Bootstrap Registration ───────────────────────────────────────────────

    /**
     * Append the new domain service provider to bootstrap/providers.php.
     *
     * Inserts a commented domain block immediately before the closing ];
     * so the file stays readable and grouped by domain.
     */
    protected function registerInBootstrap(string $domain, string $namespace, string $class): void
    {
        $bootstrapPath = base_path('bootstrap/providers.php');
        $content = $this->files->get($bootstrapPath);

        $entry = "\n    // Domain: {$domain}\n    {$namespace}\\{$class}::class,\n";

        // Anchor on the closing ]; — safe because providers.php has exactly one
        $content = preg_replace('/(\];)(\s*)$/', "{$entry}];$2", rtrim($content))."\n";

        $this->files->put($bootstrapPath, $content);

        $this->components->info("Domain [{$domain}] registered in [bootstrap/providers.php].");
    }

    // ─── Stubs ────────────────────────────────────────────────────────────────

    protected function buildFromStub(string $stubName, string $namespace, string $class): string
    {
        $stubPath = app_path("Console/stubs/domain-make/{$stubName}.stub");

        if (! $this->files->exists($stubPath)) {
            $this->components->error("Stub file not found: [{$stubPath}]");

            return '';
        }

        return str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $class],
            $this->files->get($stubPath),
        );
    }
}
