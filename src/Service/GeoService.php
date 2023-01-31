<?php

namespace GeoService\Service;

use GeoService\Http\Client;
use GeoService\Models\City;
use GeoService\Models\Country;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

class GeoService
{
    public function __construct(protected Client $client)
    {
    }

    /**
     * @param string $id
     * @return Country
     * @throws RequestException
     */
    public function getCountryWithCities(string $id): Country
    {
        return tap($this->country($id), function (Country $country) {
            $country->setCities(
                $this->getChildById($country->getId())
            );
        });
    }

    /**
     * @return Collection
     * @throws RequestException
     */
    public function countries(): Collection
    {
        return $this->client->get('countries')
            ->throw()
            ->collect('items')
            ->mapInto(Country::class);
    }

    /**
     * @param string $id
     * @return Country
     * @throws RequestException
     */
    public function country(string $id): Country
    {
        $response = $this->client->get("countries/$id")
            ->throw()
            ->json();

        return new Country($response);
    }

    /**
     * @param string $id
     * @return Country|City
     * @throws RequestException
     */
    public function getById(string $id): Country|City
    {
        $response = $this->client->get("nodes/$id")
            ->throw()
            ->json('item');

        return $response['hasChild'] ? new Country($response) : new City($response);
    }

    /**
     * @param string $id
     * @return Collection
     * @throws RequestException
     */
    public function getChildById(string $id): Collection
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