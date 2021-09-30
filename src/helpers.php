<?php

if (!function_exists('meta')) {
    function meta(): \HexideDigital\MetaTagsLaravel\Classes\Meta
    {
        return app(\HexideDigital\MetaTagsLaravel\Classes\Meta::class);
    }
}


