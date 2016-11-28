<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 31/08/2016
 * Time: 10:45
 */

namespace WonderWp\Panel;

interface PanelInterface{

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return array
     */
    public function getFields();

    /**
     * @param mixed $fields
     * @return $this
     */
    public function setFields($fields);

    /**
     * @return array
     */
    public function getPostTypes();

    /**
     * @param array $postTypes
     * @return $this
     */
    public function setPostTypes(array $postTypes);

}