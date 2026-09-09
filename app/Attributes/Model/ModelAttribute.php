<?php

namespace App\Attributes\Model;

abstract class ModelAttribute
{
    public function shouldExecute(string $hook): bool
    {
        return true;
    }

    abstract public function execute(object $target, string $hook, mixed $payload = null): void;
}
