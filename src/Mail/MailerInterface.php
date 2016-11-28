<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/09/2016
 * Time: 18:06
 */

namespace WonderWp\Mail;
use WonderWp\API\Result;

/**
 * Interface MailInterface
 * @package WonderWp\Mail
 */
interface MailerInterface
{

    /**
     * @param $subject
     * @return $this
     */
    public function setSubject($subject);

    /**
     * @param $email
     * @param string $name
     * @return $this
     */
    public function setFrom($email, $name = "");

    /**
     * @param $email
     * @param string $name
     * @return $this
     */
    public function addTo($email, $name = "");

    /**
     * @param array $tos
     * @return $this
     */
    public function addTos(array $tos);

    /**
     * @return mixed
     */
    public function getTo();

    /**
     * @param array $tos
     * @return $this
     */
    public function setTo(array $tos);

    /**
     * @param $email
     * @param string $name
     * @return $this
     */
    public function setReplyTo($email, $name = "");

    /**
     * @return mixed
     */
    public function getReplyTo();

    //Ccs
    /**
     * @param $email
     * @param string $name
     * @return $this
     */
    public function addCc($email, $name = "");

    /**
     * @param array $ccs
     * @return $this
     */
    public function addCcs(array $ccs);

    /**
     * @return mixed
     */
    public function getCc();

    /**
     * @param array $ccs
     * @return $this
     */
    public function setCc(array $ccs);

    //Bccs
    /**
     * @param $email
     * @param string $name
     * @return $this
     */
    public function addBcc($email, $name = "");

    /**
     * @param array $bccs
     * @return $this
     */
    public function addBccs(array $bccs);

    /**
     * @return $this
     */
    public function getBcc();

    /**
     * @param array $bccs
     * @return $this
     */
    public function setBcc(array $bccs);

    /**
     * @param $body
     * @return $this
     */
    public function setBody($body);

    /**
     * @param $alternativeBody
     * @return $this
     */
    public function setAltBody($alternativeBody);

    /**
     * @param $path
     * @param null $filename
     * @return $this
     */
    public function addAttachment($path, $filename = null);

    /**
     * @return Result
     */
    public function send();
}