<?php

namespace WonderWp\Framework\Form\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class UniqueEntityException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT  => [
            self::STANDARD => '{{name}} is already used',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} not found',
        ],
    ];
}
