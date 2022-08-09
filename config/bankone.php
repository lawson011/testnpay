<?php

return [

    'thirdparty-api' => [
        'base-url' => env('BANK_ONE_THIRD_PARTY_API')
    ],
    'base_url' => [
        'base_url' => env('BANK_ONE_BASE_URL'),
    ],
    'nuture-mfb' => [
        'institution-code' => env('APP_ENV') === 'local' ? env('NUTURE_MFB_INSTITUTION_CODE_TEST') : env('NUTURE_MFB_INSTITUTION_CODE_PRODUCTION'),
        'institution-token' => env('BANK_ONE_INSTITUTION_TOKEN'),
        'channel-code' => env('NUTURE_NIP_CHANNEL_CODE'),
        'nibbs_code' => env('NUTURE_NIBBS_CODE'),
        'mobile' => env('NUTURE_ADMIN_MOBILE_NUMBER')
    ],
    'nip' => [
        'channel-code' => env('NUTURE_NIP_CHANNEL_CODE')
    ],
];
