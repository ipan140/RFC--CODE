<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AntaresService
{
    protected $apiKey;
    protected $baseUrl;
    protected $appPath;

    public function __construct()
    {
        $this->apiKey = config('services.antares.api_key');
        $this->baseUrl = config('services.antares.base_url');
        $this->appPath = config('services.antares.app_path');
    }

    public function getLatestData($deviceName)
    {
        $url = "{$this->baseUrl}{$this->appPath}/{$deviceName}/la";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($url);

        if ($response->successful()) {
            return json_decode($response->body(), true);
        }

        return null;
    }
}
