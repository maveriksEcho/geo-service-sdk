<?php

namespace GeoService\Service;

use GeoService\Http\Client;
use GeoService\Models\City;
use GeoService\Models\Country;
use Illuminate\Support\Collection;

class GeoService
{
    public function __construct(protected Client $client)
    {
    }

    /**
     * @return Collection
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getCountriesWithCities(): Collection
    {
        return $this->countries()->each(function (Country $country) {
            $country->setCities(
                $this->getChildById($country->getId())
            );
        });
    }

    /**
     * @return Collection
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function countries(): Collection
    {
        return $this->client->get('countries')
            ->throw()
            ->collect('items')
            ->mapInto(Country::class);
    }

    /**
     * @param $id
     * @return Country
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function country($id): Country
    {
        $response = $this->client->get("countries/$id")
            ->throw()
            ->json('items');

        return new Country($response);
    }

    /**
     * @param $id
     * @return Country|City
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getById($id): Country|City
    {
        $response = $this->client->get("nodes/$id")
            ->throw()
            ->json('item');

        return $response['hasChild'] ? new Country($response) : new City($response);
    }

    /**
     * @param $id
     * @return Collection
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getChildById($id): Collection
    {
        return $this->client->get("nodes/$id/children")
            ->throw()
            ->collect('items');
    }

    public function ping(): bool
    {
        return $this->client->get('ping')->successful();
    }

    public function alive(): bool
    {
        return $this->client->get('utils/alive')->successful();
    }
}