<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    // https://api.open-meteo.com/v1/forecast?latitude=52.52&longitude=13.41&current=temperature_2m&hourly=temperature_2m

    public function getWeather(Request $request) {
        $validate = $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'hourly' => 'required',
            'current' => 'required',
        ]);

        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'current' => $request->current,
            'hourly' => $request->hourly
        ]);

        if ($response->successful()){
            return $response->json();
        } else {
            return response()->json([
                'error' => "Başarısız oldu"
            ], $response->status());
        }
    }
}
