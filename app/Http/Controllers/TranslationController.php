<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class TranslationController extends BaseController
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
    public function detect(Request $request)
    {
        $text = $request->input('text');
        try {
            $response = $this->client->request('POST', 'https://' . $this->host . '/v2/detect', [
                'body' => json_encode(['q' => $text]),
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                    'Content-Type' => 'application/json',
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (RequestException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ]);
        }
    }

    //get languages
    public function getLanguages()
    {
        try {
            $response = $this->client->request('GET', 'https://' . $this->host . '/v2/languages', [
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key' => $this->key,
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    //translate
    public function translate(Request $request)
    {
        $this->validate($request, [
            'text' => 'required|string',
            'source' => 'required|string',
            'target' => 'required|string',
            'format' => 'string|in:text,html'
        ]);

        $text = $request->input('text');
        $source = $request->input('source');
        $target = $request->input('target');
        $format = $request->input('format', 'text');

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

            return response()->json(json_decode($response->getBody(), true));
        } catch (RequestException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ]);
        }
    }
}
