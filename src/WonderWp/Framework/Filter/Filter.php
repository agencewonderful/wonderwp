<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/07/2017
 * Time: 17:52
 */

namespace WonderWp\Framework\Filter;

use WonderWp\Framework\Form\Field\FieldInterface;

class Filter
{
    /**
     * @var string
     */
    private $attributePath;
    /**
     * @var string
     */
    private $labelAttribute;
    /**
     * @var FieldInterface
     */
    private $field;

    /**
     * Filter constructor.
     *
     * @param FieldInterface $field
     * @param string         $attributePath
     * @param string         $labelAttribute
     */
    public function __construct(FieldInterface $field, $attributePath = '', $labelAttribute = '')
    {
        $this->field = $field;

        if (!empty($attributePath)) {
            $this->attributePath = $attributePath;
        }

        if (!empty($labelAttribute)) {
            $this->labelAttribute = $labelAttribute;
        }
    }

    /**
     * @return string
     */
    public function getAttributePath()
    {
        return !empty($this->attributePath) ? $this->attributePath : (!empty($this->getField() && !empty($this->getField()->getName())) ? $this->getField()->getName() : null);
    }

    /**
     * @param string $attributePath
     *
     * @return static
     */
    public function setAttributePath($attributePath)
    {
        $this->attributePath = $attributePath;

        return $this;
    }

    /**
     * @return FieldInterface
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param FieldInterface $field
     *
     * @return static
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelAttribute()
    {
        return $this->labelAttribute;
    }

    /**
     * @param string $labelAttribute
     *
     * @return static
     */
    public function setLabelAttribute($labelAttribute)
    {
        $this->labelAttribute = $labelAttribute;

        return $this;
    }

    public function extractValue(array $data){
        $attributePath      = $this->getAttributePath();
        $attributePathFrags = explode('.', $attributePath);

        $criteriaToFind = $data;
        if(!empty($attributePath)){
            foreach ($attributePathFrags as $attributePathFrag){
                if(isset($criteriaToFind[$attributePathFrag])){
                    $criteriaToFind = $criteriaToFind[$attributePathFrag];
                }
            }
        }
        if($criteriaToFind == $data){
            $criteriaToFind = null;
        }
        return $criteriaToFind;
    }
}
