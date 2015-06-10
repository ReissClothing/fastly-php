<?php
/**
 * @author    Gonzalo Vilaseca <gonzalo.vilaseca@reiss.com>
 * @date      10/06/15
 * @copyright Copyright (c) Reiss Clothing Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Fastly;

use GuzzleHttp\Client;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Gonzalo Vilaseca <gvilaseca@reiss.co.uk>
 */
class FastlySpec extends ObjectBehavior
{
    private $defaultHeaders = ['headers' =>
                                   [
                                       'someCustomHeader'    => 'withSomeValue',
                                       'anotherCustomHeader' => 'anotherValue'
                                   ]
    ];

    function let(Client $client)
    {
        $this->beConstructedWith($client, 'myApiKey', $this->defaultHeaders, 'http://sandbox.com');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Fastly\Fastly');
    }

    function it_merges_headers_correctly(Client $client, ResponseInterface $response)
    {
        $headers = ['headers'    =>
                        [
                            'someCustomHeader'    => 'withSomeNEWValue',
                            'anotherCustomHeader' => 'anotherValue'
                        ],
                    'someOption' => 'someValue'
        ];

        $uri = '/some/uri';

        $client->get('http://sandbox.com' . $uri, $headers)->shouldBeCalled()->willReturn($response);

        $this->send('GET', $uri, $headers)->shouldReturn($response);
    }

    function it_sends_without_endpoint(Client $client, ResponseInterface $response)
    {
        $client->put('http://someurl.com', $this->defaultHeaders)->shouldBeCalled()->willReturn($response);

        $this->sendNoEndPoint('put', 'http://someurl.com')->shouldReturn($response);
    }
}