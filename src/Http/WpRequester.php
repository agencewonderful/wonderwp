<?php

namespace WonderWp\Http;

class WpRequester extends AbstractRequester
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function doRequest(Request $request)
    {
        $http     = new \WP_Http();
        $response = $http->request((string)$request->getUri(), array(
            'method'  => $request->getMethod(),
            'headers' => $request->getHeaders(),
            'body'    => $request->getBody(),
        ));

        if ($response instanceof \WP_Error) {
            return new Response(500, $response->get_error_message(), [], $response);
        }

        return new Response($response['response']['code'], $response['response']['message'], $response['headers']->getAll(), $response['body']);
    }
}
