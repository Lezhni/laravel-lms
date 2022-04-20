<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
class CountriesService
{
    /**
     *
     */
    protected const FILE_PATH = 'misc/countries.min.json';

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getList(): Collection
    {
        $countries = $this->getDataFromFile();
        return $countries->keys();
    }

    /**
     * @param string $country
     * @return \Illuminate\Support\Collection
     */
    public function getCities(string $country): Collection
    {
        $countries = $this->getDataFromFile();
        if (! $countries->has($country)) {
            return new Collection;
        }

        return collect($countries->get($country));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getDataFromFile(): Collection
    {
        try {
            $countriesJson = Storage::get(self::FILE_PATH);
        } catch (FileNotFoundException) {
            return new Collection;
        }

        $countriesWithCities = json_decode($countriesJson, true);
        return collect($countriesWithCities);
    }
}