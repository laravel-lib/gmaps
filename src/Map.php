<?php

namespace LaravelLib\Gmaps;

use LaravelLib\Gmaps\Contracts\MappingInterface;
use LaravelLib\Gmaps\Exceptions\MapArgumentException;
use LaravelLib\Gmaps\Exceptions\MapException;
use LaravelLib\Gmaps\Exceptions\MapInstanceException;
use LaravelLib\Gmaps\Exceptions\MapSearchException;
use LaravelLib\Gmaps\Exceptions\MapSearchKeyException;
use LaravelLib\Gmaps\Exceptions\MapSearchLimitException;
use LaravelLib\Gmaps\Exceptions\MapSearchResponseException;
use LaravelLib\Gmaps\Exceptions\MapSearchResultException;
use LaravelLib\Gmaps\Exceptions\MapSearchResultMalformedException;
use LaravelLib\Gmaps\Models\Location;
use LaravelLib\Gmaps\Models\Map as ModelsMap;
use LaravelLib\Gmaps\Models\Streetview;
use Exception;

class Map extends MapBase implements MappingInterface
{
    private const GOOGLE_RESPONSE_OK = 'OK';
    private const GOOGLE_RESPONSE_ZERO_RESULTS = 'ZERO_RESULTS';
    private const GOOGLE_RESPONSE_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
    private const GOOGLE_RESPONSE_DENIED = 'REQUEST_DENIED';
    private const GOOGLE_RESPONSE_INVALID = 'INVALID_REQUEST';
    private const GOOGLE_RESPONSE_UNKNOWN = 'UNKNOWN_ERROR';

    /**
     * Renders and returns Google Map code.
     *
     * @param int $item
     *
     * @return string
     */
    public function render($item = -1)
    {
        if (!$this->isEnabled()) return;

        return $this->view->make('maps::maps')
            ->withView($this->view)
            ->withOptions($this->generateRenderOptions($item))
            ->withItems($item > -1 ? [$item => $this->getItem($item)] : $this->getItems())
            ->withTotalItems($this->getItems())
            ->render();
    }

    /**
     * Generates the render options for Google Map.
     *
     * @param int $item
     *
     * @return string
     */
    protected function generateRenderOptions($item = -1)
    {
        $options = $this->getOptions();

        foreach (($item > -1 ? [$this->getItem($item)] : $this->getItems()) as $model) {
            foreach ($model->getOptions() as $key => $option) {
                if (array_key_exists($key, $this->getOptions()) && $this->getOptions()[$key] !== $option) {
                    $options[$key] = $option;
                }
            }
        }

        return $options;
    }

    /**
     * Search for a location against Google Maps Api.
     *
     * @param string $location
     *
     * @return mixed
     */
    protected function searchLocation($location)
    {
        $request = file_get_contents(
            sprintf(
                'https://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&key=%s',
                urlencode($location),
                $this->getKey()
            )
        );

        return json_decode($request);
    }

    /**
     * Locate a location and return a Location instance.
     *
     * @param string $location
     *
     * @throws MapArgumentException
     * @throws MapSearchException
     * @throws MapSearchResponseException
     * @throws MapSearchResultException
     * @throws MapSearchKeyException
     * @throws MapSearchLimitException
     * @throws MapException
     *
     * @return Location
     */
    public function location($location)
    {
        $location = strip_tags($location);
        if (empty($location)) {
            throw new MapArgumentException('Invalid location search term provided.');
        }

        try {
            $result = $this->searchLocation($location);
        } catch (Exception $exception) {
            throw new MapSearchException('Unable to perform location search, the error was:' .
                ' "' . $exception->getMessage() .  '".');
        }

        if (
            isset($result->status) &&
            $result->status == self::GOOGLE_RESPONSE_DENIED &&
            property_exists($result, 'error_message') &&
            $result->error_message == 'The provided API key is invalid.'
        ) {
            throw new MapSearchKeyException('Unable to perform location search, provided API key is invalid.');
        }

        if (isset($result->status) && $result->status == self::GOOGLE_RESPONSE_QUERY_LIMIT) {
            throw new MapSearchLimitException('Unable to perform location search, your API key is over your quota.');
        }

        if (
            isset($result->status) &&
            in_array(
                $result->status,
                [
                    self::GOOGLE_RESPONSE_DENIED,
                    self::GOOGLE_RESPONSE_INVALID,
                    self::GOOGLE_RESPONSE_UNKNOWN
                ]
            )
        ) {
            throw new MapSearchResponseException('An error occurred performing the location search, the error was:' .
                ' "' . (property_exists($result, 'error_message') ? $result->error_message : 'Unknown') .  '".');
        }

        if (
            (isset($result->status) && $result->status == self::GOOGLE_RESPONSE_ZERO_RESULTS) ||
            !isset($result->results) ||
            (isset($result->results) && count($result->results) == 0)
        ) {
            throw new MapSearchResultException('No results found for the location search.');
        }

        if (
            !isset($result->results[0]->formatted_address) ||
            !isset($result->results[0]->address_components[0]->types) ||
            !isset($result->results[0]->geometry->location->lat) ||
            !isset($result->results[0]->geometry->location->lng) ||
            !isset($result->results[0]->place_id) ||
            isset($result->status) && $result->status != self::GOOGLE_RESPONSE_OK
        ) {
            throw new MapSearchResultMalformedException('The location search return invalid result data.');
        }

        $postalCode = null;

        foreach ($result->results[0]->address_components as $addressComponent) {
            if (count($addressComponent->types) > 0 && $addressComponent->types[0] == 'postal_code') {
                $postalCode = $addressComponent->long_name;
            }
        }

        return new Location([
            'map'        => $this,
            'search'     => $location,
            'address'    => $result->results[0]->formatted_address,
            'postalCode' => $postalCode,
            'type'       => ($result->results[0]->address_components[0]->types[0] ?? null),
            'latitude'   => $result->results[0]->geometry->location->lat,
            'longitude'  => $result->results[0]->geometry->location->lng,
            'placeId'    => $result->results[0]->place_id,
        ]);
    }

    /**
     * Add a new map.
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $options
     *
     * @return self
     */
    public function map($latitude, $longitude, array $options = [])
    {
        $parameters = array_replace_recursive(
            $this->getOptions(),
            [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'map' => 'map_' . count($this->getItems())
            ],
            $options
        );

        $item = new ModelsMap($parameters);
        $this->addItem($item);

        return $this;
    }

    /**
     * Add a new street view map.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int   $heading
     * @param int   $pitch
     * @param array $options
     *
     * @return self
     */
    public function streetview($latitude, $longitude, $heading, $pitch, array $options = [])
    {
        $parameters = array_replace_recursive(
            $this->getOptions(),
            [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'heading' => $heading,
                'pitch' => $pitch,
                'map' => 'map_' . count($this->getItems())
            ],
            $options
        );

        $item = new Streetview($parameters);
        $this->addItem($item);

        return $this;
    }

    /**
     * Add a new map marker.
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $options
     *
     * @throws MapException
     *
     * @return self
     */
    public function marker($latitude, $longitude, array $options = [])
    {
        $items = $this->getItems();

        if (empty($items)) {
            throw new MapInstanceException('No map found to add a marker to.');
        }
        $item = end($items);
        $parameters = $this->getOptions();
        $options = array_replace_recursive(
            ['markers' => $parameters['markers']],
            $item->getOptions()['markers'],
            $options
        );

        $item->marker($latitude, $longitude, $options);

        return $this;
    }

    /**
     * Add a new map information window.
     *
     * @param float  $latitude
     * @param float  $longitude
     * @param string $content
     * @param array  $options
     *
     * @throws MapException
     *
     * @return self
     */
    public function informationWindow($latitude, $longitude, $content = '', array $options = [])
    {
        $items = $this->getItems();

        if (empty($items)) {
            throw new MapInstanceException('No map found to add a information window to.');
        }

        $item = end($items);

        $parameters = $this->getOptions();
        $options = array_replace_recursive(
            ['markers' => $parameters['markers']],
            $item->getOptions()['markers'],
            $options,
            ($content !== '' ? ['markers' => ['content' => $content]] : [])
        );

        $item->marker($latitude, $longitude, $options);

        return $this;
    }

    /**
     * Add a new map polyline.
     *
     * @param array $coordinates
     * @param array $options
     *
     * @throws MapException
     *
     * @return self
     */
    public function polyline(array $coordinates = [], array $options = [])
    {
        $items = $this->getItems();

        if (empty($items)) {
            throw new MapInstanceException('No map found to add a polyline to.');
        }

        $defaults = [
            'coordinates' => $coordinates,
            'geodesic' => false,
            'strokeColor' => '#FF0000',
            'strokeOpacity' => 0.8,
            'strokeWeight' => 2,
            'editable' => false
        ];

        $item = end($items);
        $options = array_replace_recursive(
            $defaults,
            $options
        );

        $item->shape('polyline', $coordinates, $options);

        return $this;
    }

    /**
     * Add a new map polygon.
     *
     * @param array $coordinates
     * @param array $options
     *
     * @throws MapException
     *
     * @return self
     */
    public function polygon(array $coordinates = [], array $options = [])
    {
        $items = $this->getItems();

        if (empty($items)) {
            throw new MapInstanceException('No map found to add a polygon to.');
        }

        $defaults = [
            'coordinates' => $coordinates,
            'strokeColor' => '#FF0000',
            'strokeOpacity' => 0.8,
            'strokeWeight' => 2,
            'fillColor' => '#FF0000',
            'fillOpacity' => 0.35,
            'editable' => false
        ];

        $item = end($items);
        $options = array_replace_recursive(
            $defaults,
            $options
        );

        $item->shape('polygon', $coordinates, $options);

        return $this;
    }

    /**
     * Add a new map rectangle.
     *
     * @param array $coordinates
     * @param array $options
     *
     * @throws MapException
     *
     * @return self
     */
    public function rectangle(array $coordinates = [], array $options = [])
    {
        $items = $this->getItems();

        if (empty($items)) {
            throw new MapInstanceException('No map found to add a rectangle to.');
        }

        $defaults = [
            'coordinates' => $coordinates,
            'strokeColor' => '#FF0000',
            'strokeOpacity' => 0.8,
            'strokeWeight' => 2,
            'fillColor' => '#FF0000',
            'fillOpacity' => 0.35,
            'editable' => false
        ];

        $item = end($items);
        $options = array_replace_recursive(
            $defaults,
            $options
        );

        $item->shape('rectangle', $coordinates, $options);

        return $this;
    }

    /**
     * Add a new map circle.
     *
     * @param array $coordinates
     * @param array $options
     *
     * @throws MapException
     *
     * @return self
     */
    public function circle(array $coordinates = [], array $options = [])
    {
        $items = $this->getItems();

        if (empty($items)) {
            throw new MapInstanceException('No map found to add a circle to.');
        }

        $defaults = [
            'coordinates' => $coordinates,
            'strokeColor' => '#FF0000',
            'strokeOpacity' => 0.8,
            'strokeWeight' => 2,
            'fillColor' => '#FF0000',
            'fillOpacity' => 0.35,
            'radius' => 100000,
            'editable' => false
        ];

        $item = end($items);
        $options = array_replace_recursive(
            $defaults,
            $options
        );

        $item->shape('circle', $coordinates, $options);

        return $this;
    }
}
