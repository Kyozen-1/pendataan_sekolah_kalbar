<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DllController extends Controller
{
    public function getLocationAutocomplete(Request $request)
    {
        $input = $request->input('input');
        $apiKey = config('services.google_maps.api_key'); // Store API key in your config

        $response = Http::get("https://maps.googleapis.com/maps/api/place/autocomplete/json", [
            'input' => $input,
            'key' => $apiKey,
        ]);

        return $response->json();
    }
}
