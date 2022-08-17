<?php
// Copyright (c) Microsoft Corporation.
// Licensed under the MIT License.

// Access environment through the config helper
// This will avoid issues when using Laravel's config caching
// https://laravel.com/docs/8.x/configuration#configuration-caching
return [
  'appId'             => env('OAUTH_APP_ID', ''),
  'appSecret'         => env('OAUTH_APP_SECRET', ''),
  'redirectUri'       => env('OAUTH_REDIRECT_URI', ''),
  'scopes'            => env('OAUTH_SCOPES', ''),
  'authority'         => env('OAUTH_AUTHORITY', 'https://login.microsoftonline.com/common'),
  'authorizeEndpoint' => env('OAUTH_AUTHORIZE_ENDPOINT', '/oauth2/v2.0/authorize'),
  'tokenEndpoint'     => env('OAUTH_TOKEN_ENDPOINT', '/oauth2/v2.0/token'),
];