<?php
// token-exchange.php

// Exchange Google Authorization Code for Access Token
function exchange_google_token($code) {
    $token_url = "https://oauth2.googleapis.com/token";
    
    // Make POST request to Google's token endpoint
    $response = wp_remote_post($token_url, [
        'body' => [
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code'
        ]
    ]);

    return handle_token_response($response, 'google');
}

// Exchange Facebook Authorization Code for Access Token
function exchange_facebook_token($code) {
    $token_url = "https://graph.facebook.com/v11.0/oauth/access_token";
    
    // Make POST request to Facebook's token endpoint
    $response = wp_remote_post($token_url, [
        'body' => [
            'client_id' => FB_CLIENT_ID,
            'redirect_uri' => FB_REDIRECT_URI,
            'client_secret' => FB_CLIENT_SECRET,
            'code' => $code
        ]
    ]);

    return handle_token_response($response, 'facebook');
}

// Exchange Twitter OAuth Token for Access Token
function exchange_twitter_token($oauth_verifier) {
    $token_url = "https://api.twitter.com/oauth/access_token";
    
    // Make POST request to Twitter's token endpoint
    $response = wp_remote_post($token_url, [
        'body' => [
            'oauth_verifier' => $oauth_verifier,
            'client_id' => TWITTER_CLIENT_ID,
            'client_secret' => TWITTER_CLIENT_SECRET,
            'redirect_uri' => TWITTER_REDIRECT_URI
        ]
    ]);

    return handle_token_response($response, 'twitter');
}

// Handle Token Response (for all providers)
function handle_token_response($response, $provider) {
    if (is_wp_error($response)) {
        // Handle token exchange failure
        log_oauth_event("Token exchange failed for $provider: " . $response->get_error_message());
        return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (isset($body['access_token'])) {
        // Store access token securely
        store_access_token(get_current_user_id(), $provider, $body['access_token']);
        return $body['access_token'];
    } else {
        // Handle missing access token
        log_oauth_event("Access token not received for $provider.");
        return false;
    }
}
?>