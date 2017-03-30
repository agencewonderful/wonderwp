<?php

namespace WonderWp\Http;

class Request
{
    /** @var string */
    protected $method;
    /** @var string */
    protected $uri;
    /** @var string[] */
    protected $headers;
    /** @var null|string */
    protected $body;

    /**
     * Request constructor.
     *
     * @param string      $method
     * @param string      $uri
     * @param \string[]   $headers
     * @param null|string $body
     */
    public function __construct($method, $uri, array $headers = array(), $body = null)
    {
        $this->method  = $method;
        $this->uri     = $uri;
        $this->headers = $headers;
        $this->body    = $body;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return static
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return static
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return \string[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param \string[] $headers
     *
     * @return static
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param $header
     * @param $value
     *
     * @return static
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param null|string $body
     *
     * @return static
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}
