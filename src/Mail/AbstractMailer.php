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
    protected $_alt_body;

    protected $_to = array();
    protected $_cc = array();
    protected $_bcc = array();
    protected $_from = array();
    protected $_reply_to = array();
    protected $_headers = array();

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
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * @inheritdoc
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        $this->_body = $body;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAltBody()
    {
        return $this->_alt_body;
    }

    /**
     * @inheritdoc
     */
    public function setAltBody($altBody)
    {
        $this->_alt_body = $altBody;
        return $this;
    }



    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * @inheritdoc
     */
    public function setFrom($email, $name = "")
    {
        $this->_from = array($email, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReplyTo()
    {
        return $this->_reply_to;
    }

    /**
     * @inheritdoc
     */
    public function setReplyTo($email, $name = "")
    {
        $this->_reply_to = array($email, $name);
        return $this;
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function setTo(array $tos)
    {
        $this->_to = array();
        $this->addTos($tos);
    }


    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function setCc(array $ccs)
    {
        $this->_cc = array();
        $this->addCcs($ccs);
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function setBcc(array $bccs)
    {
        $this->_bcc = array();
        $this->addCcs($bccs);
    }

    /**
     * @inheritDoc
     */
    public function addAttachment($path, $filename = null)
    {
        // TODO: Implement addAttachement() method.
    }

    /**
     * @inheritdoc
     */
    public function addTo($email, $name = "")
    {
        $this->_to[$email] = array($email,$name);
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * @inheritdoc
     */
    public function addCc($email, $name = "")
    {
        $this->_cc[$email] = array($email,$name);
    }

    /**
     * @inheritdoc
     */
    public function getCc()
    {
        return $this->_cc;
    }

    /**
     * @inheritdoc
     */
    public function addBcc($email, $name = "")
    {
        $this->_bcc[$email] = array($email,$name);
    }

    /**
     * @inheritdoc
     */
    public function getBcc()
    {
        return $this->_bcc;
    }


}