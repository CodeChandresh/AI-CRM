<?php

namespace App\Config;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

return [
    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'api_secret' => env('GROQ_API_SECRET'),
        'base_url' => env('GROQ_BASE_URL'),
        'models' => [
            'customer' => [
                'type' => 'customer',
                'fields' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'created_at',
                    'updated_at',
                ],
            ],
            'lead' => [
                'type' => 'lead',
                'fields' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'created_at',
                    'updated_at',
                    'score',
                ],
            ],
            'contact' => [
                'type' => 'contact',
                'fields' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'created_at',
                    'updated_at',
                ],
            ],
        ],
    ],
];