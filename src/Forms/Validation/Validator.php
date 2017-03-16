<?php

namespace WonderWp\Forms\Validation;
use Doctrine\ORM\EntityRepository;

/**
 * @inheritdoc
 *
 * @method static Validator UniqueEntity(EntityRepository $repository, string $field, $entity = null)
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
