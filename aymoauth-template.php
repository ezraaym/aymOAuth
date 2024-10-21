<?php
/*
Template Name: Social Auth Template
*/

get_header(); // Includes the header.php file
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <h1>Social Media Authentication</h1>
        <p>Click below to authenticate with your social media account:</p>
        <!-- These links trigger social media authentication -->
        <a href="<?php echo esc_url(add_query_arg('action', 'facebook_auth', home_url())); ?>">Connect with Facebook</a><br>
        <a href="<?php echo esc_url(add_query_arg('action', 'twitter_auth', home_url())); ?>">Connect with Twitter</a><br>
        <a href="<?php echo esc_url(add_query_arg('action', 'google_auth', home_url())); ?>">Connect with Google</a>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); // Includes the footer.php file
?>
