<?php
namespace App\Services;

use GuzzleHttp\Client;

class ApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function get($url, $headers = [])
    {
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function post($url, $data = [], $headers = [])
    {
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }
}
