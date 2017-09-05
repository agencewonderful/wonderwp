<?php

namespace WonderWp\Framework\AbstractPlugin;

trait ManagerAwareTrait
{
    /** @var  AbstractManager */
    protected $manager;

    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param mixed $manager
     *
     * @return static
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }
}
