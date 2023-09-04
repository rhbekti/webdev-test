<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PengirimanController extends Controller
{
    protected $keyApi = "53e8f7700e57e060241216383004238a";

    public function city()
    {
        $response = Http::withHeaders([
            'key' => $this->keyApi
        ])->get('https://api.rajaongkir.com/starter/city');

        return $response->json();
    }

    public function ongkir(Request $request)
    {
        $request->validate([
            'origin' => 'required|numeric',
            'destination' => 'required|numeric',
            'weight' => 'required|numeric',
            'courier' => 'required'
        ]);

        $data = [
            'origin' => $request->input('origin'),
            'destination' => $request->input('destination'),
            'weight' => $request->input('weight'),
            'courier' => $request->input('courier')
        ];

        $response = Http::withHeaders([
            'key' => $this->keyApi
        ])->post('https://api.rajaongkir.com/starter/cost', $data);

        return $response->json();
    }
}
