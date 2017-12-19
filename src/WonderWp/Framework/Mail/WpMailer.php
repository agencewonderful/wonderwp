<?php

namespace WonderWp\Framework\Mail;

use WonderWp\Framework\API\Result;

class WpMailer extends AbstractMailer
{
    /** @inheritdoc */
    public function setFrom($email, $name = '')
    {
        $this->addMailHeader('From', (string)$email, (string)$name);

        return $this;
    }

    /** @inheritdoc */
    public function addTo($email, $name = "")
    {
        $this->to[] = $this->formatHeader((string)$email, (string)$name);

        return $this;
    }

    /** @inheritdoc */
    public function setReplyTo($email, $name = "")
    {
        $this->headers[] = sprintf('%s: %s', (string)'Reply-To', $this->formatHeader((string)$email, (string)$name));
    }

    /** @inheritdoc */
    public function addCc($email, $name = "")
    {
        $this->cc[] = $this->formatHeader((string)$email, (string)$name);

        return $this;
    }

    /** @inheritdoc */
    public function addBcc($email, $name = "")
    {
        $this->bcc[] = $this->formatHeader((string)$email, (string)$name);

        return $this;
    }

    /** @inheritdoc */
    public function setBody($body)
    {
        $this->body = apply_filters('wwp.mailer.setBody', str_replace("\n.", "\n..", (string)$body));

        if (strpos($this->body, '<body') !== false) {
            // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
            $this->headers[] = sprintf('%s: %s', (string)'Mime-Version', '1.0');
            $this->headers[] = sprintf('%s: %s', (string)'Content-type', 'text/html; charset=utf8');
        }

        return $this;
    }

    /** @inheritdoc */
    public function send(array $opts = [])
    {
        $to      = !(empty($this->to)) ? join(', ', $this->to) : '';
        $subject = $this->subject;
        $message = $this->body;
        $headers = $this->prepareHeaders();
        $sent    = wp_mail($to, $subject, $message, $headers);
        $code    = $sent ? 200 : 500;

        $result = new Result($code);
        return apply_filters('wwp.mailer.send.result',$result);
    }

    /**
     * array of headers to formatted string
     * @return string
     */
    public function prepareHeaders()
    {
        if (!empty($this->cc)) {
            $this->headers[] = sprintf('%s: %s', (string)'Cc', join(',', $this->cc));
        }
        if (!empty($this->bcc)) {
            $this->headers[] = sprintf('%s: %s', (string)'Bcc', join(',', $this->bcc));
        }

        return !(empty($this->headers)) ? join(PHP_EOL, $this->headers) : '';
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
        $address         = $this->formatHeader((string)$email, (string)$name);
        $this->headers[] = sprintf('%s: %s', (string)$header, $address);

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
        $words   = explode(' ', $value);
        $encoded = [];
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
        $rule  = [
            "\r" => '',
            "\n" => '',
            "\t" => '',
            '"'  => '',
            ','  => '',
            '<'  => '',
            '>'  => '',
        ];
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
        $rule     = [
            "\r" => '',
            "\n" => '',
            "\t" => '',
            '"'  => "'",
            '<'  => '[',
            '>'  => ']',
        ];
        $filtered = filter_var(
            $name,
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_NO_ENCODE_QUOTES
        );

        return trim(strtr($filtered, $rule));
    }
}
