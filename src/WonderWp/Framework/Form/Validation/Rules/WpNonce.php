<?php

namespace WonderWp\Framework\Form\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class WpNonce extends AbstractRule
{
    /** @var string|null */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /** @inheritdoc */
    public function validate($value)
    {
        return wp_verify_nonce($value, $this->name);
    }
}
