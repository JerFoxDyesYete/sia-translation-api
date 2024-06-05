<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TranslationService
{
    protected $client;
    protected $host;
    protected $key;

    public function __construct()
    {
        $this->client = new Client();
        $this->host = env('RAPIDAPI_HOST', 'google-translator9.p.rapidapi.com');
        $this->key = env('RAPIDAPI_KEY', '578df431femsh23e44eaa243f4cfp13e176jsn29a2901d6214');
    }

    //detect
    public function detectLanguage($text)
    {
        try {
            $response = $this->client->request('POST', 'https://' . $this->host . '/v2/detect', [
                'body' => json_encode(['q' => $text]),
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return [
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ];
        }
    }

    //get 
    public function getLanguages()
    {
        try {
            $response = $this->client->request('GET', 'https://' . $this->host . '/v2/languages', [
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
    //translate
    public function translate($text, $source, $target, $format = 'text')
    {
        try {
            $response = $this->client->request('POST', 'https://' . $this->host . '/v2', [
                'body' => json_encode([
                    'q' => $text,
                    'source' => $source,
                    'target' => $target,
                    'format' => $format,
                ]),
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return [
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ];
        }
    }
}
