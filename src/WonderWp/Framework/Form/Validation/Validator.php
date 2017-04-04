<?php

namespace WonderWp\Framework\Form\Validation;

/**
 * @inheritdoc
 *
 * @method static Validator WpTerm(int $parentId = null)
 * @method static Validator WpNonce(string $name)
 */
class Validator extends \Respect\Validation\Validator
{
    /** @inheritdoc */
    protected static function getFactory()
    {
        $factory = parent::getFactory();

        $rulePrefix = __NAMESPACE__ . '\\Rules';
        if (!in_array($rulePrefix, $factory->getRulePrefixes())) {
            $factory->appendRulePrefix($rulePrefix);
        }

        return $factory;
    }
}
