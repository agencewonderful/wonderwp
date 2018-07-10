<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 09/01/2018
 * Time: 18:16
 */

namespace WonderWp\Framework\CPT;

use WonderWp\Framework\Service\AbstractService;

class CustomPostTypeService extends AbstractService
{
    /** @var CustomPostType */
    protected $customPostType;

    /**
     * CustomPostTypeService constructor.
     *
     * @param CustomPostType $customPostType
     */
    public function __construct(CustomPostType $customPostType = null)
    {
        $this->customPostType = $customPostType;
    }

    /**
     * @return CustomPostType
     */
    public function getCustomPostType()
    {
        return $this->customPostType;
    }

    /**
     * @param CustomPostType $customPostType
     *
     * @return static
     */
    public function setCustomPostType($customPostType)
    {
        $this->customPostType = $customPostType;

        return $this;
    }

    public function register(){
        $this->customPostType->register();
    }
}
