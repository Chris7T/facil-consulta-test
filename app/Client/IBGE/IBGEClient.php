<?php

namespace App\Client\IBGE;

use Exception;
use Illuminate\Support\Facades\Http;

class IBGEClient
{
    private string $baseUrl;
    private array $endpoints;

    public function __construct()
    {
        $this->baseUrl = config('external-api.ibge.base_url');
        $this->endpoints = config('external-api.ibge.endpoints', []);
    }

    public function listarCidades(): array
    {
        try {
            return Http::get($this->baseUrl . $this->endpoints['cities'])
                ->throw()
                ->json();
            
        } catch (Exception) {
            return [];
        }
    }
}