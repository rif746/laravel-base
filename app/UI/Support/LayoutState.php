<?php

namespace App\UI\Support;

class LayoutState
{
    private array $breadcrumbs = [];

    public function setBreadcrumbs(array $breadcrumbs): void
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }
}
