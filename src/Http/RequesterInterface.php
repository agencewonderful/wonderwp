<?php

namespace WonderWp\Http;

interface RequesterInterface
{
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_DELETE  = 'DELETE';

    /**
     * @param string      $method
     * @param string      $uri
     * @param array       $headers
     * @param null|string $body
     *
     * @return Request
     */
    public function createRequest($method, $uri, array $headers = array(), $body = null);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function doRequest(Request $request);

    /**
     * @param string      $uri
     * @param array       $headers
     * @param null|string $body
     *
     * @return Response
     */
    public function options($uri, array $headers = array(), $body = null);

    /**
     * @param string      $uri
     * @param array       $headers
     * @param null|string $body
     *
     * @return Response
     */
    public function head($uri, array $headers = array(), $body = null);

    /**
     * @param string      $uri
     * @param array       $headers
     * @param null|string $body
     *
     * @return Response
     */
    public function get($uri, array $headers = array(), $body = null);

    /**
     * @param string      $uri
     * @param array       $headers
     * @param null|string $body
     *
     * @return Response
     */
    public function post($uri, array $headers = array(), $body = null);

    /**
     * @param string      $uri
     * @param array       $headers
     * @param null|string $body
     *
     * @return Response
     */
    public function put($uri, array $headers = array(), $body = null);

    /**
     * @param string      $uri
     * @param array       $headers
     * @param null|string $body
     *
     * @return Response
     */
    public function delete($uri, array $headers = array(), $body = null);
}
