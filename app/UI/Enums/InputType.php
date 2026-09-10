<?php

namespace App\UI\Enums;

use App\Domains\System\Concerns\Enum\HasPredicateMethod;
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
    case TEXT_LINE = 'text_line';
    case TEXT_AREA = 'text_area';
    case WYSIWYG = 'wysiwyg';
    case SELECT = 'select';
    case FILE = 'file';
    case CHECKBOX = 'checkbox';

    public function component(): string
    {
        return match ($this) {
            self::TEXT_AREA => 'form.textarea',
            self::SELECT => 'form.select',
            self::FILE => 'filepond::upload',
            self::CHECKBOX => 'form.checkbox',
            default => 'form.input',
        };
    }
}
