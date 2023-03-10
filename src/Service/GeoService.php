<?php

namespace GeoService\Service;

use GeoService\Http\Client;
use GeoService\Models\Country;
use GeoService\Models\Model;
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
    public function getCountryWithChildren(string $id): Country
    {
        return tap($this->country($id), function (Country $country) {
            $country->setChildren(
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
     * @return Model
     * @throws RequestException
     */
    public function getById(string $id): Model
    {
        $response = $this->client->get("nodes/$id", [
            'detail' => true,
            'tags' => true,
        ])
            ->throw()
            ->json('item');

        return Model::parse($response);
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

    /**
     * @param string $keyword
     * @param bool $strict
     * @param string|null $places
     * @return Collection
     */
    public function search(string $keyword, bool $strict = false, ?string $places = null): Collection
    {
        return $this->client->get('node-search', [
            'query' => $keyword,
            'query-type' => $strict ? 'city-strict' : 'city-like',
            'details' => true,
            'tags' => true,
            'places' => $places,
        ])->collect('items')->map(fn($items) => Model::parse($items));
    }

    /**
     * @return bool
     */
    public function ping(): bool
    {
        return $this->client->get('ping')->successful();
    }

    /**
     * @return bool
     */
    public function alive(): bool
    {
        return $this->client->get('utils/alive')->successful();
    }
}