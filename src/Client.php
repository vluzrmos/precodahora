<?php

namespace Vluzrmos\Precodahora;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;
use Vluzrmos\Precodahora\Queries\ProdutoQuery;

class Client
{
    protected $baseUrl = 'https://precodahora.ba.gov.br/';
    protected $httpClient;
    protected $cookieJar;

    public function __construct() {}

    protected function getDefaultHttpClientOptions()
    {
        return [
            'base_uri' => $this->baseUrl,
            'http_errors' => false,
            'cookies' => $this->getCookieJar(),
            'headers' => [
                'Accept' => '*/*',
                'Accept-Language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'Connection' => 'keep-alive',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Origin' => $this->baseUrl,
                'Referer' => $this->baseUrl,
                'Sec-Fetch-Dest' => 'empty',
                'Sec-Fetch-Mode' => 'cors',
                'Sec-Fetch-Site' => 'same-origin',
                'User-Agent' => RandomUserAgent::random(),
                'X-Csrftoken' => '',
                'X-Requested-With' => 'XMLHttpRequest'
            ],
        ];
    }

    protected function getCookieJar()
    {
        if (!$this->cookieJar) {
            $this->cookieJar = new \GuzzleHttp\Cookie\CookieJar();
        }

        return $this->cookieJar;
    }

    protected function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new \GuzzleHttp\Client(
                $this->getDefaultHttpClientOptions()
            );
        }

        return $this->httpClient;
    }

    public function getCsrfToken($url)
    {
        $response = $this->request('GET', $url);

        $dom = new Crawler((string) $response->getBody(), $url);

        $dom->filter('#validate')->each(function (Crawler $node) use (&$csrf) {
            $csrf = $node->attr('data-id');
        });

        return $csrf;
    }

    public function request($method, $uri, array $options = [])
    {
        $client = $this->getHttpClient();

        return $client->request($method, $uri, $options);
    }

    public function get($uri, $options = [])
    {
        return $this->request('GET', $uri, $options);
    }

    public function post($uri, $options = [])
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * @param ProdutoQuery $query
     * @return Responses\ProdutoResponse
     */
    public function produto(ProdutoQuery $query)
    {
        $csrfToken = $this->getCsrfToken('/produtos/');

        $options = [
            'body' => $query->getQuery(),
            'headers' => [
                'X-Csrftoken' => $csrfToken
            ]
        ];

        $data = $this->responseToJson($this->post('/produtos/', $options));

        return new Responses\ProdutoResponse($data ?: []);
    }

    protected function responseToJson(Response $response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
