<?php

namespace AppModules\Authorization\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use AppModules\Authorization\Services\Interfaces\AuthorizationServiceInterface;

class ExternalAuthorizationService implements AuthorizationServiceInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function authorize(): bool
    {
        try {
            $response = $this->client->get('https://util.devi.tools/api/v2/authorize');

            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                return isset($body['status'], $body['data']['authorization']) &&
                       $body['status'] === 'success' &&
                       $body['data']['authorization'] === true;
            }
        } catch (RequestException $e) {
            // Log the error or handle it as needed
        }

        return false;
    }
}
