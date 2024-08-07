<?php

namespace LaravelLib\Gmaps\Models;

use Illuminate\View\Factory as View;
use LaravelLib\Gmaps\Contracts\ModelingInterface;

class Marker implements ModelingInterface
{
    /**
     * Options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Public constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->options = $parameters;

        if (isset($parameters['markers'])) {
            $this->options = array_replace_recursive(
                $parameters['markers'],
                $this->options,
                ($parameters['markers']['content'] !== '' ? ['content' => $parameters['markers']['content']] : [])
            );
        }
    }

    /**
     * Render the model item.
     *
     * @param int  $identifier
     * @param View $view
     *
     * @return string
     */
    public function render($identifier, View $view)
    {
        return $view->make('maps::marker')
            ->withOptions($this->options)
            ->withId($identifier)
            ->render();
    }

    /**
     * Get the model options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
