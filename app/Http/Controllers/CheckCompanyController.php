<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckCompanyController extends Controller
{

    /**
     * Check if company name is available then return ['avaialble'=>true]
     */
    public function check(Request $request)
    {
        if (!$name = $request['name'])
            return new JsonResponse(['message' => "Missing company name!"], 400);

        $data = $this->findCompany($name);
        $response  = ['available' => $data['total_results'] == 0];
        return new JsonResponse($response);
    }

    private function findCompany(string $name)
    {

        $response = Http::withHeaders([
            'Authorization' => env('API_KEY'),
            'Content-Type' => 'application/json'
        ])->get(
            "https://api.company-information.service.gov.uk/search",
            ['q' => $name]
        );

        return  $response->json();
    }
}
