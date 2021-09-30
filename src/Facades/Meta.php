<?php

namespace HexideDigital\MetaTagsLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Meta
 * @package HexideDigital\MetaTagsLaravel\Facades
 *
 * @method static \HexideDigital\MetaTagsLaravel\Classes\Meta description(string $str)
 * @method static \HexideDigital\MetaTagsLaravel\Classes\Meta keywords(string $str)
 * @method static \HexideDigital\MetaTagsLaravel\Classes\Meta base(string $str)
 * @method static \HexideDigital\MetaTagsLaravel\Classes\Meta canonical(string $str)
 * @method static \HexideDigital\MetaTagsLaravel\Classes\Meta custom(string $str)
 * @method static \HexideDigital\MetaTagsLaravel\Classes\Meta social(string $str)
 * @method static string render()
 *
 * @mixin \HexideDigital\MetaTagsLaravel\Classes\Meta
 */
class Meta extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'meta';
    }
}
