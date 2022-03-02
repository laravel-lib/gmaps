# Google Maps API for Laravel

### Installation

```
composer require laravel-lib/gmaps
```

```
php artisan map:install
```

### Compile assets.

```
npm install && npm run dev
```

### Setting Environment (.env)

```
GOOGLE_MAPS_API_KEY="YOUR GOOGLE API KEY"
GOOGLE_MAPS_API_VERSION=quarterly
GOOGLE_MAPS_REGION=MY
GOOGLE_MAPS_LANGUAGE=ms
MIX_GOOGLE_MAPS_API_KEY="${GOOGLE_MAPS_API_KEY}"
MIX_GOOGLE_MAPS_API_VERSION="${GOOGLE_MAPS_API_VERSION}"
MIX_GOOGLE_MAPS_REGION="${GOOGLE_MAPS_REGION}"
MIX_GOOGLE_MAPS_LANGUAGE="${GOOGLE_MAPS_LANGUAGE}"
```