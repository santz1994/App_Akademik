<?php

use Intervention\Image\Drivers\Gd\Driver;

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. Depending on your PHP setup, you can choose one of them.
    |
    | Included options:
    |   - \Intervention\Image\Drivers\Gd\Driver::class
    |   - \Intervention\Image\Drivers\Imagick\Driver::class
    */

    'driver' => env('IMAGE_DRIVER', Driver::class),

    /*
    |--------------------------------------------------------------------------
    | Configuration Options
    |--------------------------------------------------------------------------
    |
    | These options control the behavior of Intervention Image.
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'backgroundColor' => 'ffffff',
        'strip' => false,
    ],

];
