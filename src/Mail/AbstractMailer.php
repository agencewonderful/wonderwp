<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 07/11/2016
 * Time: 17:56
 */

namespace WonderWp\Mail;


abstract class AbstractMailer implements MailerInterface
{

    protected $_subject;
    protected $_body;

    protected $_to = array();
    protected $_cc = array();
    protected $_bcc = array();
    protected $_from = array();
    protected $_reply_to = array();

    /**
     * __construct
     *
     * Resets the class properties.
     */
    public function __construct()
    {
        $this->reset();
    }
    /**
     * reset
     *
     * Resets all properties to initial state.
     *
     * @return $this
     */
    public function reset()
    {
        $this->_to = array();
        $this->_subject = null;
        $this->_body = null;
        $this->_headers = array();

        $this->_cc = array();
        $this->_bcc = array();
        $this->_from = array();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * @param mixed $subject
     * @return AbstractMailer
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @param mixed $body
     * @return AbstractMailer
     */
    public function setBody($body)
    {
        $this->_body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * @param array $from
     * @return AbstractMailer
     */
    public function setFrom($email, $name = "")
    {
        $this->_from = array($email, $name);
        return $this;
    }

    /**
     * @return array
     */
    public function getReplyTo()
    {
        return $this->_reply_to;
    }

    /**
     * @param array $reply_to
     * @return AbstractMailer
     */
    public function setReplyTo($email, $name = "")
    {
        $this->_reply_to = array($email, $name);
        return $this;
    }

    public function addTos(array $tos){
        if(!empty($tos)){ foreach($tos as $to){
            if(is_array($to)) {
                $mail = $to[0];
                $name = !empty($to[1]) ? $to[1] : '';
                $this->addTo($mail, $name);
            } else {
                //string so just mail
                $this->addTo($to, '');
            }
        } }
    }


    public function setTo(array $tos)
    {
        $this->_to = array();
        $this->addTos($tos);
    }



    public function addCcs(array $ccs){
        if(!empty($ccs)){ foreach($ccs as $cc){
            if(is_array($cc)) {
                $mail = $cc[0];
                $name = !empty($cc[1]) ? $cc[1] : '';
                $this->addCc($mail, $name);
            } else {
                //string so just mail
                $this->addCc($cc, '');
            }
        } }
    }



    public function setCc(array $ccs)
    {
        $this->_cc = array();
        $this->addCcs($ccs);
    }

    public function addBccs(array $bccs){
        if(!empty($bccs)){ foreach($bccs as $bcc){
            if(is_array($bcc)) {
                $mail = $bcc[0];
                $name = !empty($bcc[1]) ? $bcc[1] : '';
                $this->addCc($mail, $name);
            } else {
                //string so just mail
                $this->addCc($bcc, '');
            }
        } }
    }



    public function setBcc(array $bccs)
    {
        $this->_bcc = array();
        $this->addCcs($bccs);
    }

    /**
     * @inheritDoc
     */
    public function setAltBody($alternativeBody)
    {
        // TODO: Implement setAltBody() method.
    }

    /**
     * @inheritDoc
     */
    public function addAttachment($path, $filename = null)
    {
        // TODO: Implement addAttachement() method.
    }

    public function addTo($email, $name = "")
    {
        $this->_to[$email] = array($email,$name);
    }

    public function getTo()
    {
        return $this->_to;
    }

    public function addCc($email, $name = "")
    {
        $this->_cc[$email] = array($email,$name);
    }

    public function getCc()
    {
        return $this->_cc;
    }

    public function addBcc($email, $name = "")
    {
        $this->_bcc[$email] = array($email,$name);
    }

    public function getBcc()
    {
        return $this->_bcc;
    }


}