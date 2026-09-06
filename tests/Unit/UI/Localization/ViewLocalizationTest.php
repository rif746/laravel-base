<?php

use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

uses(TestCase::class);

test('all static localization keys in views exist in en and id languages', function () {
    $viewDir = resource_path('views');
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));

    $keys = [];

    foreach ($files as $file) {
        if (! $file->isFile()) {
            continue;
        }
        $rel = str_replace(resource_path('views').'/', '', $file->getPathname());
        if (str_starts_with($rel, 'vendor/')) {
            continue;
        }

        $content = file_get_contents($file->getPathname());

        if (preg_match_all("/(?:__|@lang|trans)\s*\(\s*[\x27\x22]([^\x27\x22]+)[\x27\x22]/", $content, $m)) {
            foreach ($m[1] as $k) {
                if (! str_ends_with($k, '.')) {
                    $keys[$k][] = $rel;
                }
            }
        }
    }

    foreach (['en', 'id'] as $locale) {
        app()->setLocale($locale);
        foreach (array_keys($keys) as $key) {
            expect(Lang::has($key, $locale))
                ->toBeTrue("Translation key '{$key}' referenced in views is missing in locale '{$locale}'");
        }
    }
});
