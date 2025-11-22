<?php
namespace Cmrweb\RequestBundle;

interface ApiRequestInterface
{ 
    public function post(string $route, ?array $options = []): static;
    public function get(string $request, array $context): static;
    public function getCurl(string $route, array $context, ?array $authBasic = null): static;
}