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
    /**
     * @var string : notification type
     */
    protected $_type;
    /**
     * @var string : notification message
     */
    protected $_message;
    /**
     * @var boolean : is notification dismissible
     */
    protected $_dismissible;

    /**
     * @var string : notification template
     */
    public static $template = '<div class="{classes}" role="alert"><p>{message}</p></div>';

    public function __construct($type,$message)
    {
        $this->_type = $type;
        $this->_message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param AdminNotification $type
     * @return $this
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
     * @param string $message
     * @return $this
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
     * @param boolean $dismissible
     * @return $this
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