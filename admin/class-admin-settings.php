<?php
// Hook to register the settings
add_action('admin_init', 'sma_register_settings');

function sma_register_settings() {
    // Add a section for display settings
    add_settings_section(
        'sma_display_section',
        'Social Media Auth Display Settings',
        'sma_display_section_callback',
        'social_media_auth_settings'
    );

    // Add a setting for displaying the Google login button
    add_settings_field(
        'sma_show_google_button',
        'Show Google Button',
        'sma_checkbox_callback',
        'social_media_auth_settings',
        'sma_display_section',
        array('id' => 'sma_show_google_button')
    );

    // Add a setting for displaying the Facebook login button
    add_settings_field(
        'sma_show_facebook_button',
        'Show Facebook Button',
        'sma_checkbox_callback',
        'social_media_auth_settings',
        'sma_display_section',
        array('id' => 'sma_show_facebook_button')
    );

    // Add a setting for displaying the Twitter login button
    add_settings_field(
        'sma_show_twitter_button',
        'Show Twitter Button',
        'sma_checkbox_callback',
        'social_media_auth_settings',
        'sma_display_section',
        array('id' => 'sma_show_twitter_button')
    );

    // Register the settings
    register_setting('social_media_auth_options', 'sma_show_google_button');
    register_setting('social_media_auth_options', 'sma_show_facebook_button');
    register_setting('social_media_auth_options', 'sma_show_twitter_button');
}

// Callback function for the section
function sma_display_section_callback() {
    echo '<p>Select which social media login buttons will be displayed and how.</p>';
}

// Callback function for the checkbox
function sma_checkbox_callback($args) {
    $option = get_option($args['id']);
    echo '<input type="checkbox" id="' . $args['id'] . '" name="' . $args['id'] . '" value="1" ' . checked(1, $option, false) . ' />';
}

// Add the settings page under the Social Media Auth plugin menu
add_action('admin_menu', 'sma_add_settings_submenu');

function sma_add_settings_submenu() {
    add_submenu_page(
        'social-media-auth-dashboard', // Main menu slug
        'Social Media Auth Settings',  // Page title
        'Settings',                    // Submenu title
        'manage_options',              // Capability
        'social_media_auth_settings',  // Submenu slug
        'sma_render_settings_page'     // Callback function
    );
}

// Render the settings page
function sma_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Social Media Auth Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('social_media_auth_options');
            do_settings_sections('social_media_auth_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
?>