<?php

namespace WonderWp\Framework\Search\Result;

class AbstractSearchResult implements SearchResultInterface
{
    protected $thumb;
    protected $title;
    protected $content;
    protected $link;

    /** @inheritdoc */
    public function getThumb()
    {
        return $this->thumb;
    }

    /** @inheritdoc */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;

        return $this;
    }

    /** @inheritdoc */
    public function getTitle()
    {
        return $this->title;
    }

    /** @inheritdoc */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /** @inheritdoc */
    public function getContent()
    {
        return $this->content;
    }

    /** @inheritdoc */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /** @inheritdoc */
    public function getLink()
    {
        return $this->link;
    }

    /** @inheritdoc */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

}
