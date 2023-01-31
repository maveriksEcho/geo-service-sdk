<?php

namespace GeoService\Models\Attributes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Tag
{
    protected string $alpha2;
    protected string $alpha3;
    protected string $numeric;
    protected array $officialName;
    protected string $isInContinent;
    protected string $type;
    protected string $adminLevel;
    protected string $boundary;
    protected string $borderType;
    protected string $defaultLanguage;
    protected string $wikidata;
    protected string $wikipedia;
    protected string $flag;

    public function __construct($tags = [])
    {
        foreach ($tags as $key => $value) {
            if (Str::contains($key, ':')) {
                [$method, $key] = explode(':', $key);

                if (method_exists($this, $method = Str::camel($method))) {
                    $this->{$method}($key, $value);
                }
            }
            if (property_exists($this, $key = Str::camel($key))) {
                $this->{$key} = $value;
            }
        }
    }

    protected function ISO31661($key, $value): void
    {
        $this->{$key} = $value;
    }

    protected function setOfficialName($key, $value): void
    {
        Arr::set($this->officialName, $key, $value);
    }
}