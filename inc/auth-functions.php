<?php
// auth-functions.php

// Function to initiate Facebook Auth Request
function initiate_facebook_auth() {
    // Generate a state parameter for security
    $state = wp_create_nonce('facebook_oauth_nonce');
    
    // Build Facebook OAuth URL
    $facebook_auth_url = "https://www.facebook.com/v11.0/dialog/oauth?" .
                         "client_id=" . urlencode(FB_CLIENT_ID) .
                         "&redirect_uri=" . urlencode(FB_REDIRECT_URI) .
                         "&scope=" . urlencode(META_SCOPES) .
                         "&response_type=code" .
                         "&state=" . urlencode($state);

    // Redirect the user to Facebook's authorization URL
    wp_redirect($facebook_auth_url);
    exit;
}

// Function to initiate Google Auth Request
function initiate_google_auth() {
    // Generate a state parameter for security
    $state = wp_create_nonce('google_oauth_nonce');

    // Build Google OAuth URL
    $google_auth_url = "https://accounts.google.com/o/oauth2/auth?" .
                       "client_id=" . urlencode(GOOGLE_CLIENT_ID) .
                       "&redirect_uri=" . urlencode(GOOGLE_REDIRECT_URI) .
                       "&scope=" . urlencode(GOOGLE_SCOPES) .
                       "&response_type=code" .
                       "&access_type=offline" .  // Request a refresh token
                       "&state=" . urlencode($state);

    // Redirect the user to Google's authorization URL
    wp_redirect($google_auth_url);
    exit;
}

// Function to initiate Twitter Auth Request
function initiate_twitter_auth() {
    // Generate a state parameter for security
    $state = wp_create_nonce('twitter_oauth_nonce');

    // Build Twitter OAuth URL
    $twitter_auth_url = "https://api.twitter.com/oauth2/authorize?" .
                        "client_id=" . urlencode(TWITTER_CLIENT_ID) .
                        "&redirect_uri=" . urlencode(TWITTER_REDIRECT_URI) .
                        "&scope=" . urlencode(TWITTER_SCOPES) .
                        "&response_type=code" .
                        "&state=" . urlencode($state);

    // Redirect the user to Twitter's authorization URL
    wp_redirect($twitter_auth_url);
    exit;
}

// General handler for social auth requests
function initiate_social_auth_request() {
    if (isset($_GET['auth_type'])) {
        $auth_type = sanitize_input($_GET['auth_type']);
        switch ($auth_type) {
            case 'facebook':
                initiate_facebook_auth();
                break;
            case 'google':
                initiate_google_auth();
                break;
            case 'twitter':
                initiate_twitter_auth();
                break;
            default:
                wp_die('Invalid social media auth type.');
        }
    } else {
        wp_die('No social media auth type provided.');
    }
}

// Sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Exchange Google authorization code for access token
function exchange_google_code_for_token($auth_code) {
    $response = wp_remote_post("https://oauth2.googleapis.com/token", [
        'body' => [
            'code' => $auth_code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code',
        ]
    ]);

    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

// Exchange Facebook authorization code for access token
function exchange_facebook_code_for_token($auth_code) {
    $response = wp_remote_post("https://graph.facebook.com/v11.0/oauth/access_token", [
        'body' => [
            'client_id' => FB_CLIENT_ID,
            'client_secret' => FB_CLIENT_SECRET,
            'redirect_uri' => FB_REDIRECT_URI,
            'code' => $auth_code,
        ]
    ]);

    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

// Exchange Twitter authorization code for access token
function exchange_twitter_code_for_token($auth_code) {
    $response = wp_remote_post("https://api.twitter.com/oauth2/token", [
        'body' => [
            'client_id' => TWITTER_CLIENT_ID,
            'client_secret' => TWITTER_CLIENT_SECRET,
            'redirect_uri' => TWITTER_REDIRECT_URI,
            'code' => $auth_code,
            'grant_type' => 'authorization_code',
        ]
    ]);

    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

// Store access tokens securely
function store_access_token($user_id, $platform, $access_token) {
    update_user_meta($user_id, $platform . '_access_token', $access_token);
}

// Fetch stored access token for a platform
function get_access_token($user_id, $platform) {
    return get_user_meta($user_id, $platform . '_access_token', true);
}
