<?php

namespace WonderWp\Framework\Search\Result;

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
