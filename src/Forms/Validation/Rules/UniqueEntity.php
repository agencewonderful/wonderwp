<?php

namespace WonderWp\Forms\Validation\Rules;

use Doctrine\ORM\EntityRepository;
use Respect\Validation\Rules\AbstractRule;

class UniqueEntity extends AbstractRule
{
    /** @var EntityRepository */
    protected $repository;
    /** @var string */
    protected $field;

    /**
     * @param EntityRepository $repository
     * @param string           $field
     */
    public function __construct(EntityRepository $repository, $field)
    {
        $this->repository = $repository;
        $this->field      = $field;
    }

    /** @inheritdoc */
    public function validate($value)
    {
        if ($value === null) {
            return true;
        }

        return count($this->repository->findBy([$this->field => $value])) === 0;
    }
}
