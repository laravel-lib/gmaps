<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Mapping
    |--------------------------------------------------------------------------
    |
    | Enable Google mapping.
    |
    */
    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Google API Key
    |--------------------------------------------------------------------------
    |
    | A Google API key.
    |
    */
    'key' => env('GOOGLE_MAPS_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Google API version
    |--------------------------------------------------------------------------
    |
    | A Google API version to use.
    |
    */
    'version' => env('GOOGLE_MAPS_API_VERSION', 'quarterly'),

    /*
    |--------------------------------------------------------------------------
    | Region
    |--------------------------------------------------------------------------
    |
    | The region Google API should use required in ISO 3166-1 code format.
    | https://developers.google.com/maps/documentation/javascript/localization?hl=en#Region
    |
    */
    'region' => env('GOOGLE_MAPS_REGION', 'MY'),

    /*
    |--------------------------------------------------------------------------
    | Language
    |--------------------------------------------------------------------------
    |
    | The Language Google API should use required in ISO 639-1 code format.
    | https://developers.google.com/maps/faq?hl=en#languagesupport
    |
    */
    'language' => env('GOOGLE_MAPS_LANGUAGE', 'ms'),

    /*
    |--------------------------------------------------------------------------
    | Location Marker
    |--------------------------------------------------------------------------
    |
    | Automatically add a location marker to the provided location.
    |
    */
    'marker' => false,

    /*
    |--------------------------------------------------------------------------
    | Center Map
    |--------------------------------------------------------------------------
    |
    | Automatically center the displayed map on the provided location.
    |
    */
    'center' => true,

    /*
    |--------------------------------------------------------------------------
    | Locate Users Location
    |--------------------------------------------------------------------------
    |
    | Automatically center the displayed map on the users current location.
    |
    */
    'locate' => false,

    /*
    |--------------------------------------------------------------------------
    | Default Zoom
    |--------------------------------------------------------------------------
    |
    | The default zoom level should use.
    |
    */
    'zoom' => 8,

    /*
    |--------------------------------------------------------------------------
    | Scroll wheel Zoom
    |--------------------------------------------------------------------------
    |
    | Set if scroll wheel zoom should be used.
    |
    */
    'scrollWheelZoom' => true,

    /*
    |--------------------------------------------------------------------------
    | Zoom Control
    |--------------------------------------------------------------------------
    |
    | Set if zoom control should be displayed.
    |
    */
    'zoomControl' => true,

    /*
    |--------------------------------------------------------------------------
    | Map Type Control
    |--------------------------------------------------------------------------
    |
    | Set if map type control should be displayed.
    |
    */
    'mapTypeControl' => true,

    /*
    |--------------------------------------------------------------------------
    | Scale Control
    |--------------------------------------------------------------------------
    |
    | Set if scale control should be displayed.
    |
    */
    'scaleControl' => false,

    /*
    |--------------------------------------------------------------------------
    | Street View Control
    |--------------------------------------------------------------------------
    |
    | Set if street view control should be displayed.
    |
    */
    'streetViewControl' => true,

    /*
    |--------------------------------------------------------------------------
    | Rotate Control
    |--------------------------------------------------------------------------
    |
    | Set if rotate control should be displayed.
    |
    */
    'rotateControl' => false,

    /*
    |--------------------------------------------------------------------------
    | Fullscreen Control
    |--------------------------------------------------------------------------
    |
    | Set if fullscreen control should be displayed.
    |
    */
    'fullscreenControl' => true,

    /*
    |--------------------------------------------------------------------------
    | Gesture Handling
    |--------------------------------------------------------------------------
    |
    | Set if gesture handling for map.
    |
    */
    'gestureHandling' => 'auto',

    /*
    |--------------------------------------------------------------------------
    | Map Type
    |--------------------------------------------------------------------------
    |
    | Set the default displayed map type. (ROADMAP|SATELLITE|HYBRID|TERRAIN)
    |
    */
    'type' => 'ROADMAP',

    /*
    |--------------------------------------------------------------------------
    | Map UI
    |--------------------------------------------------------------------------
    |
    | Should the default displayed map UI be shown.
    |
    */
    'ui' => true,

    /*
    |--------------------------------------------------------------------------
    | Map Marker
    |--------------------------------------------------------------------------
    |
    | Set the default map marker behaviour.
    |
    */
    'markers' => [

        /*
        |--------------------------------------------------------------------------
        | Marker Icon
        |--------------------------------------------------------------------------
        |
        | Display a custom icon for markers. (Link to an image)
        |
        */
        'icon' => '',

        /*
        |--------------------------------------------------------------------------
        | Marker Animation
        |--------------------------------------------------------------------------
        |
        | Display an animation effect for markers. (NONE|DROP|BOUNCE)
        |
        */
        'animation' => 'NONE',

    ],

    /*
    |--------------------------------------------------------------------------
    | Map Marker Cluster
    |--------------------------------------------------------------------------
    |
    | Enable default map marker cluster.
    |
    */
    'cluster' => false,

    /*
    |--------------------------------------------------------------------------
    | Map Marker Cluster
    |--------------------------------------------------------------------------
    |
    | Set the default map marker cluster behaviour.
    |
    */
    'clusters' => [

        /*
        |--------------------------------------------------------------------------
        | Cluster Icon
        |--------------------------------------------------------------------------
        |
        | Display custom images for clusters using icon path. (Link to an image path)
        |
        */
        'icon' => '//googlearchive.github.io/js-marker-clusterer/images/m',

        /*
        |--------------------------------------------------------------------------
        | Cluster Size
        |--------------------------------------------------------------------------
        |
        | The grid size of a cluster in pixels.
        |
        */
        'grid' => 60,

        /*
        |--------------------------------------------------------------------------
        | Cluster Zoom
        |--------------------------------------------------------------------------
        |
        | The maximum zoom level that a marker can be part of a cluster.
        |
        */
        'zoom' => null,

        /*
        |--------------------------------------------------------------------------
        | Cluster Center
        |--------------------------------------------------------------------------
        |
        | Whether the center of each cluster should be the average of all markers
        | in the cluster.
        |
        */
        'center' => false,

        /*
        |--------------------------------------------------------------------------
        | Cluster Size
        |--------------------------------------------------------------------------
        |
        | The minimum number of markers to be in a cluster before the markers are
        | hidden and a count is shown.
        |
        */
        'size' => 2
    ],
];
