<?php
// security.php

// Generate OAuth State Parameter (prevents CSRF attacks)
function generate_oauth_state($provider) {
    return wp_create_nonce($provider . '_oauth_nonce');
}

// Verify OAuth State Parameter (to prevent CSRF)
function verify_oauth_state($state, $provider) {
    return wp_verify_nonce($state, $provider . '_oauth_nonce');
}

// Sanitize input to prevent XSS and injection attacks
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Securely store access tokens using WordPress user meta
function store_access_token($user_id, $provider, $access_token) {
    update_user_meta($user_id, $provider . '_access_token', $access_token);
}

// Retrieve access tokens securely from WordPress user meta
function get_access_token($user_id, $provider) {
    return get_user_meta($user_id, $provider . '_access_token', true);
}

// Securely log OAuth errors or events
function log_oauth_event($event_message) {
    $logs = get_option('oauth_logs', []);
    $logs[] = current_time('mysql') . ': ' . $event_message;
    update_option('oauth_logs', $logs);
}
?>