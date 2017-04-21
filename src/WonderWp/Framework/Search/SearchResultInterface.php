<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 11/04/2017
 * Time: 21:40
 */

namespace WonderWp\Framework\Search;

interface SearchResultInterface
{
    /**
     * @return mixed
     */
    public function getThumb();

    /**
     * @param mixed $thumb
     *
     * @return static
     */
    public function setThumb($thumb);

    /**
     * @return mixed
     */
    public function getTitle();

    /**
     * @param mixed $title
     *
     * @return static
     */
    public function setTitle($title);

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @param mixed $content
     *
     * @return static
     */
    public function setContent($content);

    /**
     * @return mixed
     */
    public function getLink();

    /**
     * @param mixed $link
     *
     * @return static
     */
    public function setLink($link);
}
