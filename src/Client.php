<?php

declare(strict_types=1);

namespace Vluzrmos\Precodahora;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;
use Vluzrmos\Precodahora\Queries\ProdutoQuery;
use Vluzrmos\Precodahora\Responses\MunicipioResponse;
use Vluzrmos\Precodahora\Responses\ProdutoResponse;

class Client
{
    protected string $baseUrl = 'https://precodahora.ba.gov.br/';
    protected HttpClient $httpClient;
    protected CookieJar $cookieJar;

    public function __construct() {}

    protected function getDefaultHttpClientOptions(): array
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

    protected function getCookieJar(): CookieJar
    {
        if (!isset($this->cookieJar)) {
            $this->cookieJar = new CookieJar();
        }

        return $this->cookieJar;
    }

    protected function getHttpClient(): HttpClient
    {
        if (!isset($this->httpClient)) {
            $this->httpClient = new HttpClient(
                $this->getDefaultHttpClientOptions()
            );
        }

        return $this->httpClient;
    }

    public function getCsrfToken($url): ?string
    {
        $response = $this->request('GET', $url);

        $dom = new Crawler((string) $response->getBody(), $url);

        $csrf = null;

        $dom->filter('#validate')->each(function (Crawler $node) use (&$csrf) {
            $csrf = $node->attr('data-id');
        });

        return $csrf;
    }

    public function request($method, $uri, array $options = []): ResponseInterface
    {
        $client = $this->getHttpClient();

        return $client->request($method, $uri, $options);
    }

    public function get($uri, $options = []): ResponseInterface
    {
        return $this->request('GET', $uri, $options);
    }

    public function post($uri, $options = []): ResponseInterface
    {
        return $this->request('POST', $uri, $options);
    }

    public function produto(ProdutoQuery $query): ProdutoResponse
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

    public function municipios()
    {
        $csrfToken = $this->getCsrfToken('/produtos/');

        $options = [
            'headers' => [
                'X-Csrftoken' => $csrfToken
            ]
        ];

        $response = $this->post('/municipios/', $options);
        
        $data = $this->responseToJson($response);

        if (is_string($data)) { // when the response comes as a string and not a json encoded
            $data = json_decode($data, true);
        }

        return new MunicipioResponse($data);
    }

    protected function responseToJson(ResponseInterface $response): mixed
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
