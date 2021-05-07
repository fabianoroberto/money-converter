<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JsonApiTestCase extends WebTestCase
{
    protected KernelBrowser|null $client = null;
    protected string|null $token = null;

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
        $this->token = null;
    }

    public function apiRequest(string $method, string $url, array $data = [], array $headers = [])
    {
        if ($this->client === null) {
            $this->client = static::createClient();
        }

        $parsedHeaders = [];
        $defaultHeaders = ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'];

        if ($this->token !== null) {
            $defaultHeaders['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->token;
        }

        foreach ($headers as $key => $value) {
            $parsedHeaders['HTTP_' . $key] = $value;
        }

        $this->client->request(
            $method,
            $url,
            [],
            [],
            array_merge($defaultHeaders, $parsedHeaders),
            json_encode($data)
        );

        $response = $this->client->getResponse();

        return json_decode($response->getContent(), true);
    }
}
