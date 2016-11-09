<?php

namespace WonderWp\Forms\Validation;

/**
 * @inheritdoc
 *
 * @method static Validator UniqueEntity(\Doctrine\ORM\EntityRepository $repository, string $field)
 * @method static Validator WP_Term(int $parentId = null)
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
