<?php

namespace App\Domains\System\Enums;

enum InputType: string
{
    case NUMBER = 'number';
    case TEXTLINE = 'text';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case FILE = 'file';
    case CHECKBOX = 'checkbox';
}
