<?php

namespace App\Domains\System\Enums;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\AnonymousComponent;

enum InputType: string
{
    case NUMBER = 'number';
    case TEXTLINE = 'input';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case FILE = 'file';
    case CHECKBOX = 'checkbox';

    public function component(): string
    {
        return match ($this) {
            self::TEXTAREA => 'form.textarea',
            self::SELECT => 'form.select',
            self::FILE => 'filepond::upload',
            self::CHECKBOX => 'form.checkbox',
            default => 'form.input',
        };
    }
}
