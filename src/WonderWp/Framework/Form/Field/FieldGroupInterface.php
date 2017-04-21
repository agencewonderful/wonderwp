<?php

namespace WonderWp\Framework\Form\Field;

interface FieldGroupInterface extends FieldInterface
{
    /**
     * @param FieldInterface $field
     *
     * @return static
     */
    public function addFieldToGroup(FieldInterface $field);

    /**
     * @return FieldInterface[]
     */
    public function getGroup();

    /**
     * @param FieldInterface[] $group
     *
     * @return static
     */
    public function setGroup(array $group);
}
