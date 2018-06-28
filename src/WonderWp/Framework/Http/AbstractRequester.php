<?php

namespace WonderWp\Framework\Http;

abstract class AbstractRequester implements RequesterInterface
{
    /** @inheritdoc */
    public function createRequest($method, $uri, array $headers = array(), $body = null)
    {
        return new Request($method, $uri, $headers, $body);
    }

    /** @inheritdoc */
    public function options($uri, array $headers = array(), $body = null)
    {
        return $this->doRequest($this->createRequest(RequesterInterface::METHOD_OPTIONS, $uri, $headers, $body));
    }

    /** @inheritdoc */
    public function head($uri, array $headers = array(), $body = null)
    {
        return $this->doRequest($this->createRequest(RequesterInterface::METHOD_HEAD, $uri, $headers, $body));
    }

    /** @inheritdoc */
    public function get($uri, array $headers = array(), $body = null)
    {
        return $this->doRequest($this->createRequest(RequesterInterface::METHOD_GET, $uri, $headers, $body));
    }

    /** @inheritdoc */
    public function post($uri, array $headers = array(), $body = null)
    {
        return $this->doRequest($this->createRequest(RequesterInterface::METHOD_POST, $uri, $headers, $body));
    }

    /** @inheritdoc */
    public function put($uri, array $headers = array(), $body = null)
    {
        return $this->doRequest($this->createRequest(RequesterInterface::METHOD_PUT, $uri, $headers, $body));
    }

    /** @inheritdoc */
    public function delete($uri, array $headers = array(), $body = null)
    {
        return $this->doRequest($this->createRequest(RequesterInterface::METHOD_DELETE, $uri, $headers, $body));
    }
}
