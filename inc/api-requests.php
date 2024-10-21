<?php
// api-requests.php

// Function to fetch Google user profile data
function get_google_user_profile($user_id) {
    $access_token = get_access_token($user_id, 'google');
    
    if (!$access_token) {
        return false;  // Handle case where there's no valid token
    }
    
    $response = wp_remote_get("https://www.googleapis.com/oauth2/v3/userinfo?access_token=" . $access_token);
    
    if (is_wp_error($response)) {
        return false;
    } else {
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}

// Function to fetch Gmail messages for the user
function get_gmail_messages($user_id) {
    $access_token = get_user_meta($user_id, 'google_access_token', true);

    $response = wp_remote_get("https://gmail.googleapis.com/gmail/v1/users/me/messages", [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
        ]
    ]);

    if (is_wp_error($response)) {
        return false;
    } else {
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}

// Function to fetch Google Calendar events for the user
function get_google_calendar_events($user_id) {
    $access_token = get_user_meta($user_id, 'google_access_token', true);

    $response = wp_remote_get("https://www.googleapis.com/calendar/v3/calendars/primary/events", [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
        ]
    ]);

    if (is_wp_error($response)) {
        return false;
    } else {
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}

// Function to fetch YouTube channel data for the user
function get_youtube_channel_data($user_id) {
    $access_token = get_user_meta($user_id, 'google_access_token', true);

    $response = wp_remote_get("https://www.googleapis.com/youtube/v3/channels?part=snippet,contentDetails,statistics&mine=true", [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
        ]
    ]);

    if (is_wp_error($response)) {
        return false;
    } else {
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}

// Function to fetch Facebook user profile data
function get_facebook_user_profile($user_id) {
    $access_token = get_access_token($user_id, 'facebook');
    
    if (!$access_token) {
        return false;  // Handle case where there's no valid token
    }
    
    $response = wp_remote_get("https://graph.facebook.com/me?access_token=" . $access_token . "&fields=id,name,email");
    
    if (is_wp_error($response)) {
        return false;
    } else {
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}

// Function to fetch Twitter user profile data
function get_twitter_user_profile($user_id) {
    $access_token = get_access_token($user_id, 'twitter');
    
    if (!$access_token) {
        return false;  // Handle case where there's no valid token
    }
    
    $response = wp_remote_get("https://api.twitter.com/2/users/me", array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $access_token
        )
    ));
    
    if (is_wp_error($response)) {
        return false;
    } else {
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}

// Helper function to fetch Facebook user data
function get_facebook_user_data($access_token) {
    $url = 'https://graph.facebook.com/me?fields=id,name,email,picture,likes&access_token=' . $access_token;

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

?>
