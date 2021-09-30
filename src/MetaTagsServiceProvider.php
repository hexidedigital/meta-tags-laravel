<?php

namespace HexideDigital\MetaTagsLaravel;

use HexideDigital\MetaTagsLaravel\Classes\Meta;
use Illuminate\Support\ServiceProvider;

/**
 * Class MetaTagsServiceProvider
 * @package HexideDigital\MetaTagsLaravel
 */
class MetaTagsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        \Blade::directive('meta_tags_render', function (...$args){
            return \Meta::render();
        });
    }

    /**
     * register
     */
    public function register()
    {
        $this->app->singleton('meta', function () {
            return new Meta();
        });
    }
}
