<?php

return [
    'default' => env('HORIZON_QUEUE_DRIVER', 'database'),

    'connections' => [
        'database' => [
            'driver' => 'database',
            'table' => 'horizon_jobs',
            'queue' => 'default',
            'retry_after' => 90,
            'queue_retries' => 3,
            'queue_timeout' => 60,
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'default',
            'retry_after' => 90,
            'queue_retries' => 3,
            'queue_timeout' => 60,
        ],
    ],

    'failed_jobs' => [
        'driver' => 'database',
        'table' => 'failed_jobs',
    ],

    'scheduling' => [
        'run_on_reboot' => true,
        'run_on_shutdown' => true,
        'run_on_command' => true,
    ],

    'supervisor' => [
        'enabled' => true,
        'queue' => 'default',
        'max_process_exit_timeout' => 60,
        'max_attempts' => 3,
        'max_concurrency' => 5,
        'max_memory' => 128,
        'max_cpu' => 50,
    ],

    'queue' => [
        'default' => 'default',
        'connections' => [
            'default' => [
                'driver' => 'database',
                'table' => 'jobs',
                'queue' => 'default',
                'retry_after' => 90,
                'queue_retries' => 3,
                'queue_timeout' => 60,
            ],
        ],
    ],

    'ai_jobs' => [
        'queue' => 'ai',
        'max_process_exit_timeout' => 60,
        'max_attempts' => 3,
        'max_concurrency' => 5,
        'max_memory' => 128,
        'max_cpu' => 50,
    ],
];