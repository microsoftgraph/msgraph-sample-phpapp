<?php
// Copyright (c) Microsoft Corporation.
// Licensed under the MIT License.

namespace App\TokenStore;

class TokenCache {
  public function storeTokens($accessToken, $user) {
    session([
      'accessToken' => $accessToken->getToken(),
      'refreshToken' => $accessToken->getRefreshToken(),
      'tokenExpires' => $accessToken->getExpires(),
      'userName' => $user->getDisplayName(),
      'userEmail' => null !== $user->getMail() ? $user->getMail() : $user->getUserPrincipalName(),
      'userTimeZone' => $user->getMailboxSettings()->getTimeZone()
    ]);
  }

  public function clearTokens() {
    session()->forget('accessToken');
    session()->forget('refreshToken');
    session()->forget('tokenExpires');
    session()->forget('userName');
    session()->forget('userEmail');
    session()->forget('userTimeZone');
  }

  // <GetAccessTokenSnippet>
  public function getAccessToken() {
    // Check if tokens exist
    if (empty(session('accessToken')) ||
        empty(session('refreshToken')) ||
        empty(session('tokenExpires'))) {
      return '';
    }

    // Check if token is expired
    //Get current time + 5 minutes (to allow for time differences)
    $now = time() + 300;
    if (session('tokenExpires') <= $now) {
      // Token is expired (or very close to it)
      // so let's refresh

      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => config('azure.appId'),
        'clientSecret'            => config('azure.appSecret'),
        'redirectUri'             => config('azure.redirectUri'),
        'urlAuthorize'            => config('azure.authority').config('azure.authorizeEndpoint'),
        'urlAccessToken'          => config('azure.authority').config('azure.tokenEndpoint'),
        'urlResourceOwnerDetails' => '',
        'scopes'                  => config('azure.scopes')
      ]);

      try {
        $newToken = $oauthClient->getAccessToken('refresh_token', [
          'refresh_token' => session('refreshToken')
        ]);

        // Store the new values
        $this->updateTokens($newToken);

        return $newToken->getToken();
      }
      catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return '';
      }
    }

    // Token is still valid, just return it
    return session('accessToken');
  }
  // </GetAccessTokenSnippet>

  // <UpdateTokensSnippet>
  public function updateTokens($accessToken) {
    session([
      'accessToken' => $accessToken->getToken(),
      'refreshToken' => $accessToken->getRefreshToken(),
      'tokenExpires' => $accessToken->getExpires()
    ]);
  }
  // </UpdateTokensSnippet>
}
