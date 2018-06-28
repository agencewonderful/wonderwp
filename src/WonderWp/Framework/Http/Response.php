<?php

namespace WonderWp\Framework\Http;

class Response
{
    /** @var int */
    protected $status;
    /** @var string */
    protected $message;
    /** @var null|string */
    protected $body;
    /** @var array */
    protected $headers;

    /**
     * Response constructor.
     *
     * @param int         $status
     * @param string      $message
     * @param null|string $body
     * @param array       $headers
     */
    public function __construct($status, $message = '', array $headers = array(), $body = null)
    {
        $this->status  = $status;
        $this->message = $message;
        $this->body    = $body;
        $this->headers = $headers;
    }

    /**
     * @return bool
     */
    public function isInfo()
    {
        return $this->getStatus() >= 100 && $this->getStatus() < 200;
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return $this->getStatus() >= 200 && $this->getStatus() < 300;
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return $this->getStatus() >= 300 && $this->getStatus() < 400;
    }

    /**
     * @return bool
     */
    public function isClientError()
    {
        return $this->getStatus() >= 400 && $this->getStatus() < 500;
    }

    /**
     * @return bool
     */
    public function isServerError()
    {
        return $this->getStatus() >= 500 && $this->getStatus() < 600;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->isClientError() || $this->isServerError();
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return null|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string      $header
     * @param string|null $default
     *
     * @return string|null
     */
    public function getHeader($header, $default = null)
    {
        return array_key_exists($header, $this->headers) ? $this->headers[$header] : $default;
    }
}
