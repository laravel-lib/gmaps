<?php

namespace LaravelLib\Gmaps\Contracts;

use LaravelLib\Gmaps\Exceptions\MapArgumentException;
use LaravelLib\Gmaps\Exceptions\MapException;
use LaravelLib\Gmaps\Exceptions\MapSearchException;
use LaravelLib\Gmaps\Exceptions\MapSearchLimitException;
use LaravelLib\Gmaps\Exceptions\MapSearchResultException;
use LaravelLib\Gmaps\Models\Location;

interface MappingInterface
{
    /**
     * Renders and returns Google Map code.
     *
     * @param int $item
     *
     * @return string
     */
    public function render($item = -1);

    /**
     * Locate a location and return a Location instance.
     *
     * @param string $location
     *
     * @throws MapArgumentException
     * @throws MapSearchException
     * @throws MapSearchResultException
     * @throws MapSearchLimitException
     * @throws MapException
     *
     * @return Location
     */
    public function location($location);

    /**
     * Add a new map.
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $options
     *
     * @return void
     */
    public function map($latitude, $longitude, array $options = []);

    /**
     * Add a new street view map.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int   $heading
     * @param int   $pitch
     * @param array $options
     *
     * @return void
     */
    public function streetview($latitude, $longitude, $heading, $pitch, array $options = []);

    /**
     * Add a new map marker.
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $options
     *
     * @throws MapException
     *
     * @return void
     */
    public function marker($latitude, $longitude, array $options = []);

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
     * @return void
     */
    public function informationWindow($latitude, $longitude, $content, array $options = []);

    /**
     * Add a new map polyline.
     *
     * @param array $coordinates
     * @param array $options
     *
     * @throws MapException
     *
     * @return void
     */
    public function polyline(array $coordinates = [], array $options = []);

    /**
     * Add a new map polygon.
     *
     * @param array $coordinates
     * @param array $options
     *
     * @throws MapException
     *
     * @return void
     */
    public function polygon(array $coordinates = [], array $options = []);

    /**
     * Add a new map rectangle.
     *
     * @param array $coordinates
     * @param array $options
     *
     * @throws MapException
     *
     * @return void
     */
    public function rectangle(array $coordinates = [], array $options = []);

    /**
     * Add a new map circle.
     *
     * @param array $coordinates
     * @param array $options
     *
     * @throws MapException
     *
     * @return void
     */
    public function circle(array $coordinates = [], array $options = []);
}
