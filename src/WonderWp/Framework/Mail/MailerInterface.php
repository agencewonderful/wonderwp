<?php

namespace WonderWp\Framework\Mail;

use WonderWp\Framework\API\Result;
use WonderWp\Framework\Http\Response;

/**
 * Interface MailInterface
 * @package WonderWp\Mail
 */
interface MailerInterface
{

    /**
     * @param $subject
     *
     * @return static
     */
    public function setSubject($subject);

    /**
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function setFrom($email, $name = "");

    /**
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function addTo($email, $name = "");

    /**
     * @param array $tos
     *
     * @return static
     */
    public function addTos(array $tos);

    /**
     * @return mixed
     */
    public function getTo();

    /**
     * @param array $tos
     *
     * @return static
     */
    public function setTo(array $tos);

    /**
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function setReplyTo($email, $name = "");

    /**
     * @return mixed
     */
    public function getReplyTo();

    //Ccs

    /**
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function addCc($email, $name = "");

    /**
     * @param array $ccs
     *
     * @return static
     */
    public function addCcs(array $ccs);

    /**
     * @return mixed
     */
    public function getCc();

    /**
     * @param array $ccs
     *
     * @return static
     */
    public function setCc(array $ccs);

    //Bccs

    /**
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function addBcc($email, $name = "");

    /**
     * @param array $bccs
     *
     * @return static
     */
    public function addBccs(array $bccs);

    /**
     * @return static
     */
    public function getBcc();

    /**
     * @param array $bccs
     *
     * @return static
     */
    public function setBcc(array $bccs);

    /**
     * @param $body
     *
     * @return static
     */
    public function setBody($body);

    /**
     * @param $alternativeBody
     *
     * @return static
     */
    public function setAltBody($alternativeBody);

    /**
     * @param string $path
     * @param null   $filename
     *
     * @return static
     */
    public function addAttachment($path, $filename = null);

    /**
     * @param array $opts
     *
     * @return Result
     */
    public function send(array $opts = []);

}
