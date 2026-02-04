<?php

use App\Enum\AppEnvironment;
use App\Enum\UserRole;

$boolean = function (mixed $value) {
    if (in_array($value, ['true', 1, '1', true, 'yes'], true)) {
        return true;
    }

    return false;
};

$appSnakeName = strtolower(str_replace(' ', '_', $_ENV['APP_NAME']));

$appEnv = $_ENV['APP_ENV'] ?? AppEnvironment::Production->value;

return [
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'Passito',
        'logo' => $_ENV['BRAND_LOGO'],
        'desc' => $_ENV['APP_DESC'] ?? '',
        'host' => $_ENV['APP_URL'] ?? 'http://passito.local',
        'env' => $appEnv,
        'timezone' => $_ENV['TIME_ZONE'] ?? 'Asia/Kolkata',
        'version' => $_ENV['APP_VERSION'] ?? '1.0',
        'display_error_details' => $boolean($_ENV['APP_DEBUG'] ?? 0),
        'log_errors' => true,
        'log_error_details' => true,
        'qr_secret' => $_ENV['QR_SECRET'] ?? 'passito',
    ],
    'doctrine' => [
        'dev_mode' => AppEnvironment::isDevelopment($appEnv) ?? 'development',
        'cache_dir' => STORAGE_PATH . '/cache/doctrine',
        'entity_dir' => [APP_PATH . '/Entity'],
        'connection' => [
            'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'] ?? 3306,
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
        ]
    ],
    'notification' => [
        'mail' => [
            'host' => $_ENV['SMTP_HOST'],
            'user' => $_ENV['SMTP_USER'],
            'pass' => $_ENV['SMTP_PASS'],
            'from' => $_ENV['MAILER_FROM'],
            'port' => (int) $_ENV['SMTP_PORT'],
            'debug' => $boolean($_ENV['SMTP_DEBUG'])
        ],
        'sms' => [
            'twilio' => [
                'sid' => $_ENV['TWILIO_SID'],
                'from' => $_ENV['TWILIO_FROM'],
                'token' => $_ENV['TWILIO_AUTH_TOKEN'],
                'country_code' => $_ENV['TWILIO_COUNTRY_CODE'] ?? '+91',
                'verification_message' => $_ENV['TWILIO_VERIFICATION_MESSAGE'],
            ]
        ]
    ],
    'session' => [
        'name'       => $appSnakeName . '_session',
        'flash_name' => $appSnakeName . '_flash',
        'secure'     => $boolean($_ENV['SESSION_SECURE'] ?? true),
        'httponly'   => $boolean($_ENV['SESSION_HTTP_ONLY'] ?? true),
        'samesite'   => $_ENV['SESSION_SAME_SITE'] ?? 'lax',
    ],
    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'] ?? '',
        'issuer' => $_ENV['JWT_ISSUER'] ?? $appSnakeName,
        'audience' => $_ENV['JWT_AUDIENCE'] ?? $appSnakeName,
        'ttl' => (int) ($_ENV['JWT_TTL'] ?? 3600),
        'cookie' => [
            'name' => $_ENV['JWT_COOKIE_NAME'] ?? $appSnakeName . '_token',
            'secure' => $boolean($_ENV['JWT_COOKIE_SECURE'] ?? true),
            'httponly' => $boolean($_ENV['JWT_COOKIE_HTTP_ONLY'] ?? true),
            'samesite' => $_ENV['JWT_COOKIE_SAME_SITE'] ?? 'lax',
            'path' => '/',
        ],
    ],
    'csrf' => [
        'cookie' => [
            'name' => $_ENV['CSRF_COOKIE_NAME'] ?? $appSnakeName . '_csrf',
            'secure' => $boolean($_ENV['CSRF_COOKIE_SECURE'] ?? true),
            'httponly' => $boolean($_ENV['CSRF_COOKIE_HTTP_ONLY'] ?? false),
            'samesite' => $_ENV['CSRF_COOKIE_SAME_SITE'] ?? 'lax',
            'path' => '/',
        ],
        'header' => $_ENV['CSRF_HEADER_NAME'] ?? 'X-CSRF-Token',
    ],
    'view' => [
        'layouts' => [
            UserRole::USER->value => 'user.php',
            UserRole::ADMIN->value => 'admin.php',
            UserRole::SUPER_ADMIN->value => [
                'admin' => 'admin.php',
                'setup' => 'setup.php'
            ],
            'auth' => 'user.php',
            'error' => 'error.php'
        ],
        'placeholder' => [
            'contents' => '{{contents}}'
        ]
    ]
];
