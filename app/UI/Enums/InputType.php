<?php

namespace App\UI\Enums;

use App\Domains\System\Traits\Enum\HasPredicateMethod;
use App\UI\Enums\Concerns\InteractsWithLabels;
use App\UI\Enums\Contracts\HasLabel;

/**
 * @method bool isNumber()
 * @method bool isTextLine()
 * @method bool isTextArea()
 * @method bool isSelect()
 * @method bool isFile()
 * @method bool isCheckbox()
 */
enum InputType: string implements HasLabel
{
    use HasPredicateMethod;
    use InteractsWithLabels;

    case NUMBER = 'number';
    case TEXTLINE = 'text_line';
    case TEXTAREA = 'text_area';
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
