<?php
// Google OAuth Configuration
// Get credentials from: https://console.cloud.google.com

return [
    'client_id' => '360786473286-rlu9td1mm1d0vuh642n9ltv1i0g7ukpt.apps.googleusercontent.com',  // Từ Google Cloud Console
    'client_secret' => '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',  // Từ Google Cloud Console
    'redirect_uri' => 'http://localhost/DuAn1/register',  // Thay đổi theo domain thực tế
    'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
    'token_url' => 'https://oauth2.googleapis.com/token',
    'userinfo_url' => 'https://www.googleapis.com/oauth2/v2/userinfo'
];
?>
