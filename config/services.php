<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

  'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    'scheme' => 'https',
  ],

  'postmark' => [
    'token' => env('POSTMARK_TOKEN'),
  ],

  'ses' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
  ],

  // TODO: Raja Ongkir configuration
  'rajaongkir' => [
    'key' => env('RAJAONGKIR_API_KEY')
  ],

  // TODO: MIdtrans configuration
  'midtrans' => [
    // midtrans server key
    'serverKey' => env('MIDTRANS_SERVERKEY'),
    // midtrans client key
    'clientKey' => env('MIDTRANS_CLIENTKEY'),
    // Isi false jika masih tahap development, true jika sudah di production
    'isProduction' => env('MIDTRANS_IS_PRODUCTION'),
    'isSanitized'  => env('MIDTRANS_IS_SANITIZED'),
    'is3ds'        => env('MIDTRANS_IS_3DS')
  ]
];
