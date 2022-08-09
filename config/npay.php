<?php

return [
    'beneficiaries' => env('NUTURE_BENEFICIARIES_LIMIT'),
    'account_histroy_limit' => env('NUTURE_ACCOUNT_HISTORY_LIMIT'),
    'account_minimum_balance' => env('NUTURE_MINIMUM_BALANCE'),
    'gl_bills' => env('NUTURE_GL_BILLS'),
    'bills_fee' => (double)env('NUTURE_BILLS_FEE'),
];
