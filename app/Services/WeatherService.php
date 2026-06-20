<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    private string $geoUrl = 'https://geocoding-api.open-meteo.com/v1/search';
    private string $weatherUrl = 'https://api.open-meteo.com/v1/forecast';

    public function fetch(float $latitude, float $longitude): ?array
    {
        try {
            $response = Http::withoutVerifying()->get($this->weatherUrl, [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current' => 'temperature_2m,weather_code',
                'timezone' => 'auto',
            ]);
        } catch (\Exception $e) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();
        $current = $data['current'] ?? null;

        if (!$current) {
            return null;
        }

        return [
            'temperature' => $current['temperature_2m'] ?? null,
            'weather_code' => $current['weather_code'] ?? null,
            'weather_conditions' => $this->codeToDescription($current['weather_code'] ?? 0),
        ];
    }

    public function geocode(string $location): ?array
    {
        try {
            $response = Http::withoutVerifying()->get($this->geoUrl, [
                'name' => $location,
                'count' => 1,
                'language' => 'en',
                'format' => 'json',
            ]);
        } catch (\Exception $e) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $results = $response->json('results');

        if (empty($results)) {
            return null;
        }

        return [
            'latitude' => $results[0]['latitude'],
            'longitude' => $results[0]['longitude'],
            'name' => $results[0]['name'],
            'country' => $results[0]['country'] ?? '',
        ];
    }

    public function fetchByLocation(string $location): ?array
    {
        $geo = $this->geocode($location);

        // Fallback: try progressively broader parts of the address
        if (!$geo && str_contains($location, ',')) {
            $parts = array_map('trim', explode(',', $location));
            while (count($parts) > 1 && !$geo) {
                array_shift($parts);
                $geo = $this->geocode(implode(', ', $parts));
            }
        }

        // Last resort: try the broadest single term
        if (!$geo && str_contains($location, ',')) {
            $parts = array_map('trim', explode(',', $location));
            $geo = $this->geocode(end($parts));
        }

        if (!$geo) {
            return null;
        }

        $weather = $this->fetch($geo['latitude'], $geo['longitude']);

        if (!$weather) {
            return null;
        }

        $weather['location'] = $geo['name'] . ', ' . $geo['country'];

        return $weather;
    }

    private function codeToDescription(int $code): string
    {
        return match(true) {
            $code === 0 => 'Clear sky',
            $code <= 3 => 'Partly cloudy',
            $code <= 19 => 'Foggy',
            $code <= 29 => 'Thunderstorm',
            $code <= 39 => 'Drizzle',
            $code <= 49 => 'Rainy',
            $code <= 59 => 'Freezing rain',
            $code <= 69 => 'Snowy',
            $code <= 79 => 'Snow grains',
            $code <= 89 => 'Rain showers',
            $code <= 99 => 'Thunderstorm',
            default => 'Unknown',
        };
    }
}
