import { Loader } from '@googlemaps/js-api-loader';

window.initMaps = [];
window.maps = [];

window.mapLoader = new Loader({
    apiKey: process.env.MIX_GOOGLE_MAPS_API_KEY,
    version: process.env.MIX_GOOGLE_MAPS_API_VERSION,
    language: process.env.MIX_GOOGLE_MAPS_LANGUAGE,
    region: process.env.MIX_GOOGLE_MAPS_API_REGION,
    libraries: ['places']
});
