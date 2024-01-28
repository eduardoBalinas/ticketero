<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Ticketero extends Controller
{
    public function destination(Request $request)
    {

        $dataBase64 = $request->input("data");
        $dataEntry = $this->decodeRequest($dataBase64);

        $startDate = $dataEntry["startDate"];
        $endDate = $dataEntry["endDate"];
        $searchType = $dataEntry["searchType"];
        $withPerformers = false;
        $latitude = $dataEntry["latitude"];
        $longitude = $dataEntry["longitude"];
        $radius = $dataEntry["radius"];
        $city = $dataEntry["city"];

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'searchType' => $searchType,
            'withPerformers' => $withPerformers,
            'destination' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius,
                'city' => $city,
            ],
        ];


        $response = $this->requestApi($data);

        return response()->json($response);
    }

    public function venue(Request $request)
    {

        $dataBase64 = $request->input("data");
        $dataEntry = $this->decodeRequest($dataBase64);


        $searchType = $dataEntry["searchType"];
        $venueId = $dataEntry["venueId"];
        $withPerformers = false;

        $data = [
            'searchType' => $searchType,
            'venueId' => $venueId,
            'withPerformers' => $withPerformers,
        ];


        $response = $this->requestApi($data);

        return response()->json($response);
    }

    public function performe(Request $request)
    {

        $dataBase64 = $request->input("data");
        $dataEntry = $this->decodeRequest($dataBase64);

        $searchType = $dataEntry["searchType"];
        $performerId = $dataEntry["performerId"];
        $withPerformers = false;

        $data = [
            'searchType' => $searchType,
            'performerId' => $performerId,
            'withPerformers' => $withPerformers,
        ];

        $response = $this->requestApi($data);

        // Manejar la respuesta aquí...
        return response()->json($response);
    }

    private function decodeRequest($dataBase64)
    {
        $jsonString = base64_decode($dataBase64);
        $jsonData = json_decode($jsonString, true);
        return $jsonData;
    }

    private function requestApi($data)
    {

        $token = config('app.token_ticketero');
        $url = config("app.url_ticketero");

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
