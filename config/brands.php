<?php

return [
    'cache' => [
        'prefix' => 'brand-domain:v1:',
        'positive_ttl_seconds' => 600,
        'negative_ttl_seconds' => 60,
    ],

    'host_validation' => [
        'operational_hosts' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('BRAND_OPERATIONAL_HOSTS', ''))
        ))),
        'local_compatibility_hosts' => [
            'localhost',
            '127.0.0.1',
        ],
        'local_context_hostname' => env('BRAND_LOCAL_CONTEXT_HOSTNAME') ?: 'maacdurgapur.local',
    ],

    'admin_context' => [
        'session_key' => 'admin_brand_context',
    ],

    'dynamic_forms' => [
        'maac' => (bool) env('DYNAMIC_FORMS_MAAC', true),
        'aksha' => (bool) env('DYNAMIC_FORMS_AKSHA', true),
        'space-e-fic' => (bool) env('DYNAMIC_FORMS_SPACE_E_FIC', true),
    ],

    'settings' => [
        'supported_locales' => ['en'],
        'default_locale' => 'en',
    ],
];
