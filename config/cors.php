<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], //astikan hanya berisi array string P
    'allowed_methods' => ['*'],
    'allowed_origins' => ['https://klp4.masjidin.my.id'], // Ubah jika perlu
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
