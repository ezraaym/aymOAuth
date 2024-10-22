<?php
/*
Plugin Name: Custom Hooks for PMPro and JetEngine
Description: Automatically create and update JetEngine CPT posts based on PMPro profile updates, membership changes, and new user registrations.
Version: 1.1
Author: aym
Author URI: https://www.aymscores.com
*/

// 1. Runs when a user profile is updated
add_action('profile_update', 'my_profile_update_function', 10, 2);
function my_profile_update_function($user_id, $old_user_data) {
    // Log the profile update for testing
    error_log('User profile updated for user ID: ' . $user_id);

    // Update the JetEngine CPT post (if exists)
    update_jetengine_post($user_id);
}

// 2. Runs when a PMPro membership level changes
add_action('pmpro_after_change_membership_level', 'my_membership_change_function', 10, 2);
function my_membership_change_function($level_id, $user_id) {
    // Log the membership level change
    error_log('Membership level changed for user ID: ' . $user_id . ' to level: ' . $level_id);

    // Update or create a CPT post based on the membership level change
    update_jetengine_post($user_id, $level_id);
}

// 3. Runs when a new user is created
add_action('user_register', 'my_user_created_function', 10, 1);
function my_user_created_function($user_id) {
    // Log the new user creation
    error_log('New user registered with user ID: ' . $user_id);

    // Create a new JetEngine CPT post for the new user
    create_jetengine_post($user_id);
}

// Function to create a new JetEngine CPT post for a new user
function create_jetengine_post($user_id) {
    $user_info = get_userdata($user_id);
    error_log('Creating new CPT post for user ID: ' . $user_id);

    $post_data = array(
        'post_title'    => $user_info->user_login,
        'post_content'  => '', // Can be filled with other user data
        'post_status'   => 'publish',
        'post_type'     => 'user-intent', // Replace with your actual CPT slug
    );

    // Insert the post and log the post ID
    $post_id = wp_insert_post($post_data);
    error_log('New post created with ID: ' . $post_id);

    // Add user meta fields and log them
    if ($post_id) {
        update_post_meta($post_id, 'username', $user_info->user_login);
        error_log('Added username: ' . $user_info->user_login);

        update_post_meta($post_id, 'email', $user_info->user_email);
        error_log('Added email: ' . $user_info->user_email);

        update_post_meta($post_id, 'user_id', $user_id); // Add the User ID to the CPT meta field
        error_log('Added user_id: ' . $user_id);
    }
}

// Function to update an existing JetEngine CPT post based on user data
function update_jetengine_post($user_id, $level_id = null) {
    $user_info = get_userdata($user_id);

    // Log the user info and level ID to check if the function is receiving them correctly
    error_log('Updating JetEngine CPT for user ID: ' . $user_id);
    error_log('User login: ' . $user_info->user_login);
    error_log('Level ID: ' . $level_id);

    // Check if a CPT post exists for the user
    $args = array(
        'post_type' => 'user-intent', // Replace with your CPT slug
        'meta_key' => 'username',
        'meta_value' => $user_info->user_login,
        'posts_per_page' => 1,
    );

    $existing_post = get_posts($args);

    if ($existing_post) {
        // Post found, log the post ID
        $post_id = $existing_post[0]->ID;
        error_log('Found existing post with ID: ' . $post_id);

        // Updating the post meta
        error_log('Updating post meta for user ID: ' . $user_id);
        update_post_meta($post_id, 'email', $user_info->user_email);

        // Update CPT with PMPro custom fields
        $fields = get_pmpro_custom_fields($user_id);
        foreach ($fields as $meta_key => $meta_value) {
            update_post_meta($post_id, $meta_key, $meta_value);
            error_log('Updated post meta: ' . $meta_key . ' = ' . $meta_value);
        }

        // Log membership level update if applicable
        if ($level_id) {
            update_post_meta($post_id, 'membership_level', $level_id);
            error_log('Updated membership level: ' . $level_id);
        }
    } else {
        // No post found, log that we're creating a new post
        error_log('No post found. Creating a new one for user ID: ' . $user_id);
        create_jetengine_post($user_id);
    }
}

// Function to retrieve PMPro custom fields
function get_pmpro_custom_fields($user_id) {
    $fields = array(
        'what_industry_do_you_see_yourself_in' => get_user_meta($user_id, 'what_industry_do_you_see_yourself_in', true),
        'are_you_employed' => get_user_meta($user_id, 'are_you_employed', true),
        'what_is_your_target_market' => get_user_meta($user_id, 'what_is_your_target_market', true),
        'what_is_your_desired_outcome' => get_user_meta($user_id, 'what_is_your_desired_outcome', true),
        'what_skills_and_expertise_do_you_have' => get_user_meta($user_id, 'what_skills_and_expertise_do_you_have', true),
        'what_stage_is_your_business_in' => get_user_meta($user_id, 'what_stage_is_your_business_in', true),
        'what_assets_do_you_have' => get_user_meta($user_id, 'what_assets_do_you_have', true),
        'what_are_your_revenue_goals' => get_user_meta($user_id, 'what_are_your_revenue_goals', true),
    );

    // Log the custom fields being fetched
    error_log('Retrieved custom fields for user ID: ' . $user_id);
    foreach ($fields as $key => $value) {
        error_log('Field: ' . $key . ' = ' . $value);
    }

    return $fields;
}

// Function to periodically check if all PMPro entries are synced with CPTs
function check_and_sync_cpts_with_pmpro() {
    // Get all PMPro members
    $pmpro_members = get_users(array('role' => 'pmpro_member_role')); // Replace with actual PMPro role

    foreach ($pmpro_members as $member) {
        $user_id = $member->ID;

        // Check if CPT exists for this member
        $args = array(
            'post_type' => 'user-intent', // Replace with your CPT slug
            'meta_key' => 'username',
            'meta_value' => $member->user_login,
            'posts_per_page' => 1,
        );
        $existing_post = get_posts($args);

        if (!$existing_post) {
            // If no CPT found, create one
            create_jetengine_post($user_id);
        }
    }
}
add_action('wp_scheduled_event', 'check_and_sync_cpts_with_pmpro');
