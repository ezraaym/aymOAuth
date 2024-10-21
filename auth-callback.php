<?php
// Load WordPress environment
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

// Check which provider is sending the response
if (isset($_GET['provider'])) {
    $provider = sanitize_text_field($_GET['provider']);
    switch ($provider) {
        case 'google':
            handle_google_callback();
            break;
        case 'facebook':
            handle_facebook_callback();
            break;
        case 'twitter':
            handle_twitter_callback();
            break;
        default:
            wp_die('Invalid provider.');
    }
} else {
    wp_die('No provider specified.');
}

// Handle Google OAuth Callback
function handle_google_callback() {
    if (isset($_GET['code']) && isset($_GET['state']) && wp_verify_nonce($_GET['state'], 'google_oauth_nonce')) {
        $code = sanitize_text_field($_GET['code']);
        // Exchange code for token using the token-exchange.php function
        $access_token = exchange_google_token($code);
        if ($access_token) {
            // Redirect to success page
            wp_redirect(home_url('/dashboard'));
            exit;
        } else {
            wp_die('Google token exchange failed.');
        }
    } else {
        wp_die('Invalid OAuth response.');
    }
}

// Handle Facebook OAuth Callback
function handle_facebook_callback() {
    if (isset($_GET['code']) && isset($_GET['state']) && wp_verify_nonce($_GET['state'], 'facebook_oauth_nonce')) {
        $code = sanitize_text_field($_GET['code']);
        // Exchange code for token using the token-exchange.php function
        $access_token = exchange_facebook_token($code);
        if ($access_token) {
            // Redirect to success page
            wp_redirect(home_url('/dashboard'));
            exit;
        } else {
            wp_die('Facebook token exchange failed.');
        }
    } else {
        wp_die('Invalid OAuth response.');
    }
}

// Handle Twitter OAuth Callback
function handle_twitter_callback() {
    if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) && wp_verify_nonce($_GET['state'], 'twitter_oauth_nonce')) {
        $oauth_verifier = sanitize_text_field($_GET['oauth_verifier']);
        // Exchange code for token using the token-exchange.php function
        $access_token = exchange_twitter_token($oauth_verifier);
        if ($access_token) {
            // Redirect to success page
            wp_redirect(home_url('/dashboard'));
            exit;
        } else {
            wp_die('Twitter token exchange failed.');
        }
    } else {
        wp_die('Invalid OAuth response.');
    }
}
?>