<?php

namespace Tests\Helpers;

use Illuminate\Support\Facades\Artisan;

class Seeder
{
    public static function categories()
    {
        Artisan::call('beam:taxonomies:seed', [
            '--path' => __DIR__ . '/../__database__/seeds/taxonomies',
        ]);
    }
}
