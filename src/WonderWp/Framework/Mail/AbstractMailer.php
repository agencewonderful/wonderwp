<?php

namespace WonderWp\Framework\Mail;

abstract class AbstractMailer implements MailerInterface
{
    /** @var string */
    protected $subject;
    /** @var string */
    protected $body;
    /** @var string */
    protected $altBody;

    /** @var array */
    protected $to = [];
    /** @var array */
    protected $cc = [];
    /** @var array */
    protected $bcc = [];
    /** @var array */
    protected $from = [];
    /** @var array */
    protected $replyTo = [];
    /** @var array */
    protected $headers = [];

    /**
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
     * @return static
     */
    public function reset()
    {
        $this->to      = [];
        $this->subject = null;
        $this->body    = null;
        $this->headers = [];

        $this->cc   = [];
        $this->bcc  = [];
        $this->from = [];

        return $this;
    }

    /** @inheritdoc */
    public function getSubject()
    {
        return $this->subject;
    }

    /** @inheritdoc */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /** @inheritdoc */
    public function getBody()
    {
        return $this->body;
    }

    /** @inheritdoc */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /** @inheritdoc */
    public function getAltBody()
    {
        return $this->altBody;
    }

    /** @inheritdoc */
    public function setAltBody($altBody)
    {
        $this->altBody = $altBody;

        return $this;
    }

    /** @inheritdoc */
    public function getFrom()
    {
        return $this->from;
    }

    /** @inheritdoc */
    public function setFrom($email, $name = "")
    {
        $this->from = [$email, $name];

        return $this;
    }

    /** @inheritdoc */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /** @inheritdoc */
    public function setReplyTo($email, $name = "")
    {
        $this->replyTo = [$email, $name];

        return $this;
    }

    /** @inheritdoc */
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

    /** @inheritdoc */
    public function setTo(array $tos)
    {
        $this->to = [];
        $this->addTos($tos);

        return $this;
    }

    /** @inheritdoc */
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

    /** @inheritdoc */
    public function setCc(array $ccs)
    {
        $this->cc = [];
        $this->addCcs($ccs);

        return $this;
    }

    /** @inheritdoc */
    public function addBccs(array $bccs)
    {
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

        return $this;
    }

    /** @inheritdoc */
    public function setBcc(array $bccs)
    {
        $this->bcc = [];
        $this->addBccs($bccs);

        return $this;
    }

    /** @inheritdoc */
    public function addAttachment($path, $filename = null)
    {
        // TODO: Implement addAttachement() method.
        return $this;
    }

    /** @inheritdoc */
    public function addTo($email, $name = "")
    {
        $this->to[$email] = [$email, $name];

        return $this;
    }

    /** @inheritdoc */
    public function getTo()
    {
        return $this->to;
    }

    /** @inheritdoc */
    public function addCc($email, $name = "")
    {
        $this->cc[$email] = [$email, $name];

        return $this;
    }

    /** @inheritdoc */
    public function getCc()
    {
        return $this->cc;
    }

    /** @inheritdoc */
    public function addBcc($email, $name = "")
    {
        $this->bcc[$email] = [$email, $name];

        return $this;
    }

    /** @inheritdoc */
    public function getBcc()
    {
        return $this->bcc;
    }
}
