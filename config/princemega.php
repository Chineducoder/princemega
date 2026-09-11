<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    */

    'name' => 'Prince Mega Agency',
    'tagline' => "Nigeria's Cosmetics Giant",

    'hubs_caption' => 'Rumuola Head Office · Mile 1 Diobu · Woji Plaza Hubs',

    /*
    |--------------------------------------------------------------------------
    | Commerce
    |--------------------------------------------------------------------------
    */

    'currency' => 'NGN',
    'currency_symbol' => '₦',

    /*
    | Delivery fee for retail orders within Port Harcourt once the
    | free-delivery threshold is passed. Orders below the threshold
    | carry this flat fee. Wholesale orders ship free by policy.
    */
    'delivery' => [
        'flat_fee' => 1500,
        'free_threshold' => 50000,
        'default_state' => 'Rivers',
        'default_city' => 'Port Harcourt',
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp order export
    |--------------------------------------------------------------------------
    | The destination number lives in the environment — never hard-code it.
    | International format, digits only: 2348012345678
    */

    'whatsapp_number' => env('PRINCE_MEGA_WHATSAPP_NUMBER', ''),

    'whatsapp' => [
        'message_signature' => env('WHATSAPP_MESSAGE_SIGNATURE', 'Prince Mega Agency'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Receipt uploads
    |--------------------------------------------------------------------------
    */

    'receipts' => [
        'disk' => 'local',
        'max_kb' => 4096,
        'mimes' => 'jpg,jpeg,png,webp,pdf',
    ],

];
