<?php

namespace WonderWp\Forms\Validation\Rules;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Respect\Validation\Rules\AbstractRule;
use WonderWp\DI\Container;

class UniqueEntity extends AbstractRule
{
    /** @var EntityRepository */
    protected $repository;
    /** @var string */
    protected $field;
    /** @var  Entity */
    protected $entity;

    /**
     * @param EntityRepository $repository
     * @param string $field
     */
    public function __construct(EntityRepository $repository, $field, $entity = null)
    {
        $this->repository = $repository;
        $this->field = $field;
        $this->entity = $entity;
    }

    /** @inheritdoc */
    public function validate($value)
    {
        if ($value === null) {
            return true;
        }

        $container = Container::getInstance();
        /** @var EntityManager $entityManager */
        $entityManager = $container->offsetGet('entityManager');
        $isPersisted = is_object($this->entity) && $entityManager->contains($this->entity);
        if($isPersisted) {
            $meta = $entityManager->getClassMetadata(get_class($this->entity));
            $identifier = $meta->getSingleIdentifierFieldName();
            $qb = $entityManager->createQueryBuilder();
            $expr = $entityManager->getExpressionBuilder();

            $qb->select( 'entity' )
                ->from( get_class($this->entity), 'entity' )
                ->andWhere('entity.'.$this->field.' = (:val)')
                ->andWhere( $expr->neq( 'entity.' . $identifier, $this->entity->$identifier ) )
                ->setParameter('val',$value)
                ;

            return count($qb->getQuery()->getResult()) === 0;
        } else {
            return count($this->repository->findBy([$this->field => $value])) === 0;
        }
    }
}
