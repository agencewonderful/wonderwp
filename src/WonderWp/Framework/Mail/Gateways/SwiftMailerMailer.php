<?php

namespace WonderWp\Framework\Mail\Gateways;

use WonderWp\Framework\API\Result;
use WonderWp\Framework\DependencyInjection\Container;
use WonderWp\Framework\Mail\AbstractMailer;

class SwiftMailerMailer extends AbstractMailer
{
    private $message;

    /** @inheritdoc */
    public function __construct()
    {
        parent::__construct();

        $this->message = \Swift_Message::newInstance();
    }

    /** @inheritdoc */
    public function setSubject($subject)
    {
        $this->message->setSubject($subject);

        return $this;
    }

    /** @inheritdoc */
    public function setFrom($email, $name = "")
    {
        $this->message->setFrom([$email => $name]);

        return $this;
    }

    /** @inheritdoc */
    public function addTo($email, $name = "")
    {
        $this->message->addTo($email, $name);

        return $this;
    }

    /** @inheritdoc */
    public function addCc($email, $name = "")
    {
        $this->message->addCc($email, $name);

        return $this;
    }

    /** @inheritdoc */
    public function addBcc($email, $name = "")
    {
        $this->message->addBcc($email, $name);

        return $this;
    }

    /** @inheritdoc */
    public function setBody($body)
    {
        $body = apply_filters('wwp.mailer.setBody', str_replace("\n.", "\n..", (string)$body));
        $this->message->setBody($body, 'text/html');

        return $this;
    }

    /** @inheritdoc */
    public function send(array $opts = [])
    {
        $container = Container::getInstance();
        $transport = $container->offsetExists('wwp.emails.mailer.swift_transport') ? $container->offsetGet('wwp.emails.mailer.swift_transport') : \Swift_MailTransport::newInstance();
        $mailer    = \Swift_Mailer::newInstance($transport);
        $nbSent    = $mailer->send($this->message);
        $code      = $nbSent > 0 ? 200 : 500;
        $result    = new Result($code, ['res' => $nbSent, 'successes' => $nbSent, 'failures' => null]);

        return $result;
    }
}
