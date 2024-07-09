<?php

namespace LaravelLib\Gmaps\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MapInstallCommand extends Command
{
    public $signature = 'map:install';
    public $description = 'Install the Google Maps assets.';

    public function handle()
    {
        $this->updateNodePackages(function ($packages) {
            return [
                '@googlemaps/js-api-loader' => '^1.16.6',
                'js-marker-clusterer' => '^1.0.0',
            ] + $packages;
        });

        // Copy config files and maps view blade files
        File::copy(__DIR__ . '/../../config/config.php', config_path('maps.php'));
        File::copyDirectory(__DIR__ . '/../../resources/views', base_path('resources/views/maps'));

        // Copy maps.js to resources/js/components/maps.js
        File::ensureDirectoryExists(resource_path('js/components'));
        File::copy(__DIR__ . '/../../resources/js/maps.js', resource_path('js/components/maps.js'));
        $this->injectGmaps();

        $this->info('Google Maps package has been successfully installed.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');
    }

    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (!File::exists(base_path('package.json'))) return;

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(File::get(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        File::put(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    private function injectGmaps(): void
    {
        File::put(
            resource_path('js/app.js'),
            "\n\n// Load Google Maps components\nimport './components/maps';\nimport 'js-marker-clusterer';\n\n"
        );
    }
}
