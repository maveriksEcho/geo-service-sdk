<?php

namespace GeoService\Models\Attributes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Tag
{
    protected string $alpha2;
    protected string $alpha3;
    protected string $numeric;
    protected array $officialName = [];
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
            } elseif (property_exists($this, $key = Str::camel($key))) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    /**
     * @return string
     */
    public function getAlpha3(): string
    {
        return $this->alpha3;
    }

    /**
     * @return string
     */
    public function getNumeric(): string
    {
        return $this->numeric;
    }

    /**
     * @return array|string|null
     */
    public function getOfficialName(?string $key = null, string $default = null): mixed
    {
        return Arr::get($this->officialName, $key, $default);
    }

    /**
     * @return string
     */
    public function getIsInContinent(): string
    {
        return $this->isInContinent;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getBoundary(): string
    {
        return $this->boundary;
    }

    public function getBorderType(): string
    {
        return $this->borderType;
    }

    /**
     * @return string
     */
    public function getAdminLevel(): string
    {
        return $this->adminLevel;
    }

    /**
     * @return string
     */
    public function getDefaultLanguage(): string
    {
        return $this->defaultLanguage;
    }

    public function getWikidata(): string
    {
        return $this->wikidata;
    }

    public function getWikipedia(): string
    {
        return $this->wikipedia;
    }

    public function getFlag(): string
    {
        return $this->flag;
    }

    protected function ISO31661($key, $value): void
    {
        $this->{$key} = $value;
    }

    protected function officialName($key, $value): void
    {
        Arr::set($this->officialName, $key, $value);
    }
}