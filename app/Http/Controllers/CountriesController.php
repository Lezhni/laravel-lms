<?php

namespace App\Http\Controllers;

use App\Services\CountriesService;
use Illuminate\Http\JsonResponse;

/**
 *
 */
class CountriesController extends Controller
{
    /**
     * @var \App\Services\CountriesService
     */
    protected CountriesService $countriesService;

    /**
     * @param \App\Services\CountriesService $countriesService
     */
    public function __construct(CountriesService $countriesService)
    {
        $this->countriesService = $countriesService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountries(): JsonResponse
    {
        $countries = $this->countriesService->getList();

        return new JsonResponse([
            'countries' => $countries,
        ]);
    }

    public function getCountryCities(string $country): JsonResponse
    {
        $cities = $this->countriesService->getCities($country);

        return new JsonResponse([
            'cities' => $cities,
        ]);
    }
}
