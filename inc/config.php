<?php
// config.php

// Define API credentials using environment variables
define('FB_CLIENT_ID', getenv('FB_CLIENT_ID') ?: '');
define('FB_CLIENT_SECRET', getenv('FB_CLIENT_SECRET') ?: '');
define('FB_REDIRECT_URI', getenv('FB_REDIRECT_URI') ?: '');

define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI') ?: '');

define('TWITTER_CLIENT_ID', getenv('TWITTER_CLIENT_ID') ?: '');
define('TWITTER_CLIENT_SECRET', getenv('TWITTER_CLIENT_SECRET') ?: '');
define('TWITTER_REDIRECT_URI', getenv('TWITTER_REDIRECT_URI') ?: '');

// Define scopes for each service
define('META_SCOPES', 'email, public_profile, user_posts, pages_read_engagement, pages_manage_posts, instagram_basic, instagram_manage_comments');
define('GOOGLE_SCOPES', 'profile email https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/gmail.readonly https://www.googleapis.com/auth/contacts.readonly https://www.googleapis.com/auth/youtube.readonly');
define('TWITTER_SCOPES', 'read write direct_messages');

// Validate essential variables
if (!FB_CLIENT_ID || !FB_CLIENT_SECRET || !FB_REDIRECT_URI) {
    error_log('Facebook API credentials are missing.');
}

if (!GOOGLE_CLIENT_ID || !GOOGLE_CLIENT_SECRET || !GOOGLE_REDIRECT_URI) {
    error_log('Google API credentials are missing.');
}

if (!TWITTER_CLIENT_ID || !TWITTER_CLIENT_SECRET || !TWITTER_REDIRECT_URI) {
    error_log('Twitter API credentials are missing.');
}

?>
