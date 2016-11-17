<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 15/09/2016
 * Time: 18:08
 */

namespace WonderWp\Mail;


use WonderWp\API\Result;

class WpMailer extends AbstractMailer
{

    protected $_headers = array();

    /**
     * setFrom
     *
     * @param string $email The email to send as from.
     * @param string $name  The name to send as from.
     *
     * @return $this
     */
    public function setFrom($email, $name='')
    {
        $this->addMailHeader('From', (string) $email, (string) $name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addTo($email, $name = "")
    {
        $this->_to[] = $this->formatHeader((string) $email, (string) $name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setReplyTo($email, $name = "")
    {
        $this->_headers[] = sprintf('%s: %s', (string)'Reply-To', $this->formatHeader((string) $email, (string) $name));
    }

    /**
     * @inheritDoc
     */
    public function addCc($email, $name = "")
    {
        $this->_cc[] = $this->formatHeader((string) $email, (string) $name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addBcc($email, $name = "")
    {
        $this->_bcc[] = $this->formatHeader((string) $email, (string) $name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBody($body)
    {
        $this->_body = apply_filters('wwp.mailer.setBody',str_replace("\n.", "\n..", (string) $body));

        if(strpos($this->_body,'<body')!==false){
            // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
            $this->_headers[] = sprintf('%s: %s', (string)'Mime-Version', '1.0');
            $this->_headers[] = sprintf('%s: %s', (string)'Content-type', 'text/html; charset=utf8');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function send()
    {
        $to = !(empty($this->_to)) ? join(', ', $this->_to) : '';
        $subject = $this->_subject;
        $message = $this->_body;
        $headers = $this->prepareHeaders();

        /*echo'<br />To';
        \WonderWp\trace($to);
        echo'<br /><br />Subject';
        \WonderWp\trace($subject);
        echo'<br /><br />Message';
        \WonderWp\trace($message);
        echo'<br /><br />Headers';
        \WonderWp\trace($headers);
        return true;*/

        $sent = wp_mail($to, $subject, $message, $headers);
        $code = $sent ? 200 : 500;

        return new Result($code);
    }

    public function prepareHeaders(){

        if(!empty($this->_cc)){
            $this->_headers[] = sprintf('%s: %s', (string)'Cc', join(',',$this->_cc));
        }
        if(!empty($this->_bcc)){
            $this->_headers[] = sprintf('%s: %s', (string)'Bcc', join(',',$this->_bcc));
        }

        return !(empty($this->_headers)) ? join(PHP_EOL, $this->_headers) : '';
    }

    /**
     * addMailHeader
     *
     * @param string $header The header to add.
     * @param string $email  The email to add.
     * @param string $name   The name to add.
     *
     * @return $this
     */
    public function addMailHeader($header, $email = null, $name = null)
    {
        $address = $this->formatHeader((string) $email, (string) $name);
        $this->_headers[] = sprintf('%s: %s', (string) $header, $address);
        return $this;
    }

    /**
     * formatHeader
     *
     * Formats a display address for emails according to RFC2822 e.g.
     * Name <address@domain.tld>
     *
     * @param string $email The email address.
     * @param string $name  The display name.
     *
     * @return string
     */
    public function formatHeader($email, $name = null)
    {
        $email = $this->filterEmail($email);
        if (empty($name)) {
            return $email;
        }
        $name = $this->encodeUtf8($this->filterName($name));
        return sprintf('"%s" <%s>', $name, $email);
    }
    /**
     * encodeUtf8
     *
     * @param string $value The value to encode.
     *
     * @return string
     */
    public function encodeUtf8($value)
    {
        $value = trim($value);
        if (preg_match('/(\s)/', $value)) {
            return $this->encodeUtf8Words($value);
        }
        return $this->encodeUtf8Word($value);
    }
    /**
     * encodeUtf8Word
     *
     * @param string $value The word to encode.
     *
     * @return string
     */
    public function encodeUtf8Word($value)
    {
        return sprintf('=?UTF-8?B?%s?=', base64_encode($value));
    }
    /**
     * encodeUtf8Words
     *
     * @param string $value The words to encode.
     *
     * @return string
     */
    public function encodeUtf8Words($value)
    {
        $words = explode(' ', $value);
        $encoded = array();
        foreach ($words as $word) {
            $encoded[] = $this->encodeUtf8Word($word);
        }
        return join($this->encodeUtf8Word(' '), $encoded);
    }
    /**
     * filterEmail
     *
     * Removes any carriage return, line feed, tab, double quote, comma
     * and angle bracket characters before sanitizing the email address.
     *
     * @param string $email The email to filter.
     *
     * @return string
     */
    public function filterEmail($email)
    {
        $rule = array(
            "\r" => '',
            "\n" => '',
            "\t" => '',
            '"'  => '',
            ','  => '',
            '<'  => '',
            '>'  => ''
        );
        $email = strtr($email, $rule);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $email;
    }
    /**
     * filterName
     *
     * Removes any carriage return, line feed or tab characters. Replaces
     * double quotes with single quotes and angle brackets with square
     * brackets, before sanitizing the string and stripping out html tags.
     *
     * @param string $name The name to filter.
     *
     * @return string
     */
    public function filterName($name)
    {
        $rule = array(
            "\r" => '',
            "\n" => '',
            "\t" => '',
            '"'  => "'",
            '<'  => '[',
            '>'  => ']',
        );
        $filtered = filter_var(
            $name,
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_NO_ENCODE_QUOTES
        );
        return trim(strtr($filtered, $rule));
    }

}