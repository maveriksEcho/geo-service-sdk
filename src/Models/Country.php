<?php

namespace GeoService\Models;

use Illuminate\Support\Collection;

class Country extends Model
{
    protected Collection $cities;

    public function __construct($data = [])
    {
        $this->setCities(new Collection);
        parent::__construct($data);
    }

    /**
     * @return Collection
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city)
    {
        $this->cities->push($city);
    }

    /**
     * @param Collection $cities
     */
    public function setCities(Collection $cities): void
    {
        $this->cities = $cities;
    }
}