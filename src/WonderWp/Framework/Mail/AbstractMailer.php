<?php

namespace WonderWp\Framework\Mail;

abstract class AbstractMailer implements MailerInterface
{
    protected $_subject;
    protected $_body;
    protected $_alt_body;

    protected $_to       = [];
    protected $_cc       = [];
    protected $_bcc      = [];
    protected $_from     = [];
    protected $_reply_to = [];
    protected $_headers  = [];

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
        $this->_to      = [];
        $this->_subject = null;
        $this->_body    = null;
        $this->_headers = [];

        $this->_cc   = [];
        $this->_bcc  = [];
        $this->_from = [];

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
        $this->_from = [$email, $name];

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
        $this->_reply_to = [$email, $name];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addTos(array $tos)
    {
        if (!empty($tos)) {
            foreach ($tos as $to) {
                if (is_array($to)) {
                    $mail = $to[0];
                    $name = !empty($to[1]) ? $to[1] : '';
                    $this->addTo($mail, $name);
                } else {
                    //string so just mail
                    $this->addTo($to, '');
                }
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTo(array $tos)
    {
        $this->_to = [];
        $this->addTos($tos);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addCcs(array $ccs)
    {
        if (!empty($ccs)) {
            foreach ($ccs as $cc) {
                if (is_array($cc)) {
                    $mail = $cc[0];
                    $name = !empty($cc[1]) ? $cc[1] : '';
                    $this->addCc($mail, $name);
                } else {
                    //string so just mail
                    $this->addCc($cc, '');
                }
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCc(array $ccs)
    {
        $this->_cc = [];
        $this->addCcs($ccs);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addBccs(array $bccs)
    {
        if (!empty($bccs)) {
            foreach ($bccs as $bcc) {
                if (is_array($bcc)) {
                    $mail = $bcc[0];
                    $name = !empty($bcc[1]) ? $bcc[1] : '';
                    $this->addCc($mail, $name);
                } else {
                    //string so just mail
                    $this->addCc($bcc, '');
                }
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setBcc(array $bccs)
    {
        $this->_bcc = [];
        $this->addBccs($bccs);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addAttachment($path, $filename = null)
    {
        // TO DO: Implement addAttachement() method.
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addTo($email, $name = "")
    {
        $this->_to[$email] = [$email, $name];

        return $this;
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
        $this->_cc[$email] = [$email, $name];

        return $this;
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
        $this->_bcc[$email] = [$email, $name];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBcc()
    {
        return $this->_bcc;
    }

}
