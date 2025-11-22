<?php

namespace Cmrweb\RequestBundle;

interface ApiRequestInterface
{
    public function post(string $route, ?array $options = []): static;
    public function get(string $route, ?array $context = null, ?array $options = []): static;
    public function getCurl(string $route, array $context, ?array $auth = null): static;
}
