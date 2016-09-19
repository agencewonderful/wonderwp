<?php

namespace WonderWp\Notification;

/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 19/09/2016
 * Time: 09:46
 */
class AdminNotification
{
    private $_type;
    private $_message;
    private $_dismissible;

    public static $template = '<div class="{classes}"><p>{message}</p></div>';

    public function __construct($type,$message)
    {
        $this->_type = $type;
        $this->_message = $message;
        return $this;
    }

    /**
     * @return AdminNotification
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param AdminNotification $type
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDismissible()
    {
        return $this->_dismissible;
    }

    /**
     * @param mixed $dismissible
     */
    public function setDismissible($dismissible)
    {
        $this->_dismissible = $dismissible;
        return $this;
    }

    /**
     * @return string
     */
    public function getMarkup(){
        $classes = 'notice notice-'.$this->_type;
        if($this->getDismissible()){
            $classes.=' is-dismissible';
        }
        $markup=str_replace(array('{classes}','{message}'),array($classes,$this->_message),self::$template);
        return $markup;
    }

    public function __toString()
    {
        return $this->getMarkup();
    }


}