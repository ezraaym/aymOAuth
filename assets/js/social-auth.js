// social-auth.js

document.addEventListener('DOMContentLoaded', function() {
  // Example: Handle login button click for Facebook
  document.getElementById('facebook-login-btn').addEventListener('click', function() {
    window.location.href = '/?action=initiate_social_auth&auth_type=facebook';
  });

  // Handle other social media login clicks similarly...
});