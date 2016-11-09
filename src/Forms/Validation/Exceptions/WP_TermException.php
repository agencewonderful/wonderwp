<?php

namespace WonderWp\Forms\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class WP_TermException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT  => [
            self::STANDARD => '{{name}} is not valid',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} is not valid',
        ],
    ];
}
