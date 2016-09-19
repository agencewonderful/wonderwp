<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/09/2016
 * Time: 18:06
 */

namespace WonderWp\Mail;

/**
 * Interface MailInterface
 * @package WonderWp\Mail
 */
interface MailerInterface
{

    /**
     * @param $subject
     * @return mixed
     */
    public function setSubject($subject);

    /**
     * @param $email
     * @param string $name
     * @return mixed
     */
    public function setFrom($email, $name = "");

    /**
     * @param $email
     * @param string $name
     * @return mixed
     */
    public function addTo($email, $name = "");

    public function addTos(array $tos);

    public function getTo();

    public function setTo(array $tos);

    /**
     * @param $email
     * @param string $name
     * @return mixed
     */
    public function setReplyTo($email, $name = "");

    //Ccs
    public function addCc($email, $name = "");

    public function addCcs(array $ccs);

    public function getCc();

    public function setCc(array $ccs);

    //Bccs
    public function addBcc($email, $name = "");

    public function addBccs(array $bccs);

    public function getBcc();

    public function setBcc(array $bccs);

    /**
     * @param $body
     * @return mixed
     */
    public function setBody($body);

    /**
     * @param $alternativeBody
     * @return mixed
     */
    public function setAltBody($alternativeBody);

    /**
     * @param $attachement
     * @return mixed
     */
    public function addAttachment($path, $filename = null);

    /**
     * @return bool
     */
    public function send();
}