<?php

namespace WonderWp\Framework\Form;

use WonderWp\Framework\Form\Field\FieldInterface;

interface FormViewInterface
{
    /**
     * @param FormInterface $form
     *
     * @return static
     */
    public function setFormInstance(FormInterface $form);

    /**
     * @return FormInterface
     */
    public function getFormInstance();

    /**
     * @param array $opts
     *
     * @return string
     */
    public function render(array $opts = []);

    /**
     * @param array $optsStart
     *
     * @return string
     */
    public function formStart(array $optsStart = []);

    /**
     * @param array $optsBeforeFields
     *
     * @return string
     */
    public function formBeforeFields($optsBeforeFields = []);

    /**
     * @return string
     */
    public function formErrors();

    /**
     * @param FormGroup $group
     *
     * @return string
     */
    public function renderGroup(FormGroup $group);

    /**
     * @param FieldInterface|string $field
     *
     * @return string
     */
    public function renderField($field);

    /**
     * @param FieldInterface|string $field
     *
     * @return string
     */
    public function fieldWrapStart($field);

    /**
     * @param FieldInterface|string $field
     *
     * @return string
     */
    public function fieldLabel($field);

    /**
     * @param FieldInterface|string $field
     *
     * @return string
     */
    public function fieldStart($field);

    /**
     * @param FieldInterface|string $field
     *
     * @return string
     */
    public function fieldBetween($field);

    /**
     * @param FieldInterface|string $field
     *
     * @return string
     */
    public function fieldEnd($field);

    /**
     * @param FieldInterface|string $field
     *
     * @return string
     */
    public function fieldError($field);

    /**
     * @param string $field
     *
     * @return string
     */
    public function fieldHelp($field);

    /**
     * @param FieldInterface|string $field
     *
     * @return string
     */
    public function fieldWrapEnd($field);

    /**
     * @param array $optsEnd
     *
     * @return string
     */
    public function formEnd(array $optsEnd = []);
}
