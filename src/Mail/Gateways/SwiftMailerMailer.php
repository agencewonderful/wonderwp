<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 24/03/2017
 * Time: 11:47
 */

namespace WonderWp\Mail\Gateways;


use WonderWp\API\Result;
use WonderWp\Mail\AbstractMailer;

class SwiftMailerMailer extends AbstractMailer
{

    private $_message;

    public function __construct()
    {
        parent::__construct();
        $this->_message = \Swift_Message::newInstance();
        return $this;
    }

    public function setSubject($subject)
    {
        $this->_message->setSubject($subject);
        return $this;
    }

    public function setFrom($email, $name = "")
    {
        $this->_message->setFrom([$email=>$name]);
        return $this;
    }

    public function addTo($email, $name = "")
    {
        $this->_message->addTo($email,$name);
        return $this;
    }

    public function addCc($email, $name = "")
    {
        $this->_message->addCc($email,$name);
        return $this;
    }

    public function addBcc($email, $name = "")
    {
        $this->_message->addBcc($email,$name);
        return $this;
    }

    public function setBody($body)
    {
        $this->_message->setBody($body);
        return $this;
    }

    /**
     * @return Result
     */
    public function send()
    {

    }
}
