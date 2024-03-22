<?php

$boolean = function (mixed $value) {
    if (in_array($value, ['true', 1, '1', true, 'yes'], true)) {
        return true;
    }

    return false;
};

$appSnakeName = strtolower(str_replace(' ', '_', $_ENV['APP_NAME']));

return [
    'app' => [
        'name' => $_ENV['APP_NAME'],
        'desc' => $_ENV['APP_DESC'],
        'host' => $_ENV['APP_URL'] ?? 'http://localhost',
        'version' => $_ENV['APP_VERSION'] ?? '1.0',
        'display_error_details' => $boolean($_ENV['APP_DEBUG'] ?? 0),
        'log_errors' => true,
        'log_error_details' => true,
    ],
    'db' => [
        'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASS'],
    ],
    'mailer' => [
        'dsn' => $_ENV['MAILER_DSN'],
        'from' => $_ENV['MAILER_FROM']
    ],
    'session' => [
        'name'       => $appSnakeName . '_session',
        'flash_name' => $appSnakeName . '_flash',
        'secure'     => $boolean($_ENV['SESSION_SECURE'] ?? true),
        'httponly'   => $boolean($_ENV['SESSION_HTTP_ONLY'] ?? true),
        'samesite'   => $_ENV['SESSION_SAME_SITE'] ?? 'lax',
    ],
    'view' => [
        'base_view' => 'master.php',
        'placeholder' => [
            'header' => '{{header}}',
            'contents' => '{{contents}}',
            'footer' => '{{footer}}'
        ]
    ]
];