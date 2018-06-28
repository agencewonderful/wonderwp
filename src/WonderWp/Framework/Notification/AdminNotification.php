<?php

namespace WonderWp\Framework\Notification;

class AdminNotification
{
    /** @var string */
    public static $template = '<div class="{classes}" role="alert"><p>{message}</p></div>';
    /** @var string */
    protected $type;
    /** @var string */
    protected $message;
    /** @var boolean */
    protected $dismissible;

    /**
     * @codeCoverageIgnore
     * @param string $type
     * @param string $message
     */
    public function __construct($type, $message)
    {
        $this->type    = $type;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMarkup()
    {
        $classes = 'notice notice-' . $this->getType();
        if ($this->isDismissible()) {
            $classes .= ' is-dismissible';
        }
        $markup = str_replace(['{classes}', '{type}', '{message}'], [$classes, $this->getType(), $this->getMessage()], static::$template);

        return $markup;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMarkup();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @codeCoverageIgnore
     *
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @codeCoverageIgnore
     * @param string $message
     *
     * @return static
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return bool
     */
    public function isDismissible()
    {
        return $this->dismissible;
    }

    /**
     * @codeCoverageIgnore
     * @param boolean $dismissible
     *
     * @return static
     */
    public function setDismissible($dismissible)
    {
        $this->dismissible = $dismissible;

        return $this;
    }
}
