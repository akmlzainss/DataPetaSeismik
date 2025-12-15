<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */

    'session' => [
        'regenerate_interval' => env('SESSION_REGENERATE_INTERVAL', 300), // 5 minutes
        'max_lifetime' => env('SESSION_MAX_LIFETIME', 7200), // 2 hours
    ],

    'rate_limiting' => [
        'login_attempts' => env('LOGIN_RATE_LIMIT', 5),
        'password_reset_attempts' => env('PASSWORD_RESET_RATE_LIMIT', 3),
        'contact_form_attempts' => env('CONTACT_FORM_RATE_LIMIT', 5),
    ],

    'backup' => [
        'enabled' => env('BACKUP_ENABLED', true),
        'retention_days' => env('BACKUP_RETENTION_DAYS', 30),
        'storage_disk' => env('BACKUP_STORAGE_DISK', 'local'),
    ],

    'security_headers' => [
        'hsts' => [
            'enabled' => env('HSTS_ENABLED', true),
            'max_age' => env('HSTS_MAX_AGE', 31536000), // 1 year
            'include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
        ],
        'csp' => [
            'enabled' => env('CSP_ENABLED', true),
            'report_only' => env('CSP_REPORT_ONLY', false),
        ],
    ],

    'file_upload' => [
        'max_size' => env('FILE_UPLOAD_MAX_SIZE', 51200), // 50MB in KB
        'allowed_mimes' => [
            'jpeg',
            'png',
            'jpg',
            'gif',
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx'
        ],
        'scan_for_viruses' => env('FILE_SCAN_VIRUSES', false),
    ],
];
