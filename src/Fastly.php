<?php

namespace Fastly;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Gonzalo Vilaseca <gonzalo.vilaseca@reiss.com>
 */
class Fastly
{
    /**
     * Http client
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * Fastly API entry point
     *
     * @var string
     */
    private $entryPoint;

    /**
     * Default headers
     *
     * @var array
     */
    private $defaultHeaders;

    /**
     * @param ClientInterface $client
     * @param string          $entryPoint
     * @param string          $fastlyKey
     * @param array           $defaultHeaders
     */
    public function __construct(
        ClientInterface $client,
        $fastlyKey,
        $defaultHeaders = [],
        $entryPoint = 'https://api.fastly.com'
    )
    {
        $this->client         = $client;
        $this->defaultHeaders = array_merge(
            ['headers' => [
                'Fastly-Key' => $fastlyKey,
                'Accept'     => 'application/json',
            ]],
            $defaultHeaders
        );
        $this->entryPoint     = $entryPoint;
    }

    /**
     * Send http request
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function send($method, $uri, array $options = [])
    {
        return $this->client->$method($this->entryPoint . $uri, array_merge($this->defaultHeaders, $options));
    }

    /**
     * Send http response without prepending API end point
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function sendNoEndPoint($method, $url, array $options = [])
    {
        return $this->client->$method($url, array_merge($this->defaultHeaders, $options));
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function purge($url, array $options = [])
    {
        return $this->sendNoEndPoint('PURGE', $url, $options);
    }

    /**
     * @param string $service
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function purgeAll($service, array $options = [])
    {
        $url = '/service/' . urlencode($service) . '/purge_all';

        return $this->send('POST', $url, $options);
    }

    /**
     * @param string $service
     * @param string $key
     * @param array  $options
     *
     * @return ResponseInterface
     */
    public function purgeKey($service, $key, array $options = [])
    {
        $url = '/service/' . urlencode($service) . '/purge/' . $key;

        return $this->send('POST', $url, $options);
    }
}
