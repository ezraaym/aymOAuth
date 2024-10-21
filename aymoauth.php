<?php
/*
Plugin Name: Social Media Auth
Description: Handles social media OAuth authentication.
Version: 1.0
Author: aym
Author URI: https://www.aymscores.com
*/

// Include the settings file
require_once __DIR__ . '/admin/class-admin-settings.php';

// Include the auth-functions file
require_once __DIR__ . '/inc/auth-functions.php';

// Define Constants for API Credentials (ensure these environment variables are set correctly)
define('FB_CLIENT_ID', getenv('FB_CLIENT_ID'));
define('FB_REDIRECT_URI', getenv('FB_REDIRECT_URI'));
define('META_SCOPES', 'email, public_profile, user_posts, pages_manage_posts, instagram_basic, instagram_manage_comments');

define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI'));
define('GOOGLE_SCOPES', 'profile email https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/gmail.readonly https://www.googleapis.com/auth/contacts.readonly https://www.googleapis.com/auth/youtube.readonly');

define('TWITTER_CLIENT_ID', getenv('TWITTER_CLIENT_ID'));
define('TWITTER_CLIENT_SECRET', getenv('TWITTER_CLIENT_SECRET'));
define('TWITTER_REDIRECT_URI', getenv('TWITTER_REDIRECT_URI'));
define('TWITTER_SCOPES', 'read write direct_messages');

// Add a shortcode to display social auth links
add_shortcode('social_auth', 'social_auth_shortcode');

// Shortcode to create the social auth links
function social_auth_shortcode() {
    $google_auth_url = esc_url(add_query_arg('auth_type', 'google', home_url('/?action=initiate_social_auth')));
    $facebook_auth_url = esc_url(add_query_arg('auth_type', 'facebook', home_url('/?action=initiate_social_auth')));
    $twitter_auth_url = esc_url(add_query_arg('auth_type', 'twitter', home_url('/?action=initiate_social_auth')));

    $output = '';

    // Show buttons based on the settings
    if (get_option('sma_show_google_button')) {
        $output .= '<a href="' . $google_auth_url . '">Connect with Google</a><br>';
    }
    if (get_option('sma_show_facebook_button')) {
        $output .= '<a href="' . $facebook_auth_url . '">Connect with Facebook</a><br>';
    }
    if (get_option('sma_show_twitter_button')) {
        $output .= '<a href="' . $twitter_auth_url . '">Connect with Twitter</a><br>';
    }

    return $output;
}

// Add Admin Menu for the Plugin Dashboard
add_action('admin_menu', 'social_media_auth_add_admin_menu');

function social_media_auth_add_admin_menu() {
    add_menu_page(
        'Social Media Auth Dashboard', // Page title
        'Social Media Auth',           // Menu title
        'manage_options',              // Capability
        'social-media-auth-dashboard', // Menu slug
        'social_media_auth_dashboard', // Callback function
        'dashicons-admin-generic',     // Icon
        6                              // Position
    );
}

// Render the dashboard page
function social_media_auth_dashboard() {
    ?>
    <div class="wrap">
        <h1>Social Media Auth Dashboard</h1>
        <p>Welcome to the Social Media Auth Dashboard. Use the settings tab to configure display options.</p>
    </div>
    <?php
}
?>
