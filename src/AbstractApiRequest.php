<?php

namespace Cmrweb\RequestBundle;
 
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\CurlHttpClient; 
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractApiRequest implements ApiRequestInterface
{ 
    private ?ResponseInterface $response = null;

    public function __construct(
        protected string $url,
        private readonly HttpClientInterface $httpClient,
        protected readonly ParameterBagInterface $param
    ) {}

    abstract protected function apiRequest(string $type, ?array $context = null): array;

    protected function getContent(): string
    {
        return $this->response->getContent();
    }

    protected function getData(): array
    {
        return $this->response->toArray();
    }
    
    public function post(string $route, ?array $options = []): static
    {
        $request = $this->url . $route;
        return $this->exceptionHandler($this->httpClient->request('POST', $request, $options)); 
    }

    public function get(string $route, ?array $context = null, ?array $options = []): static
    {
        $query = $context ? '?'.http_build_query($context) : '';
        $request = $this->url . $route . $query; 
        return $this->exceptionHandler($this->httpClient->request('GET', $request, $options));
    }

    public function getCurl(string $route, array $context, ?array $auth = null): static
    {
        $requestUrl = implode('', [$this->url, $route, '?', http_build_query($context)]);
        $curlCLient = new CurlHttpClient();
        $request = $context;
        array_push($request, $auth); 
        return $this->exceptionHandler($curlCLient->request('GET', $requestUrl, $request));    
    }

    private function exceptionHandler(ResponseInterface $response): static
    {
        $this->response = null; 
        if(200 === $response->getStatusCode()) {
            $this->response = $response;
        }  
        return $this;
    }
}
