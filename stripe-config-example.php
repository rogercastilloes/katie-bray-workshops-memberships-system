<?php
/**
 * Stripe Configuration Example
 * 
 * Copy this file to wp-config.php or your theme's functions.php
 * Replace the placeholder values with your actual Stripe API keys
 */

// Stripe Configuration
define('STRIPE_TEST_PUBLISHABLE_KEY', 'pk_test_your_test_publishable_key_here');
define('STRIPE_TEST_SECRET_KEY', 'sk_test_your_test_secret_key_here');
define('STRIPE_LIVE_PUBLISHABLE_KEY', 'pk_live_your_live_publishable_key_here');
define('STRIPE_LIVE_SECRET_KEY', 'sk_live_your_live_secret_key_here');

// Stripe Webhook Endpoint (configure in Stripe Dashboard)
// URL: https://yourdomain.com/stripe-webhook
// Events to listen for:
// - checkout.session.completed
// - invoice.payment_succeeded
// - invoice.payment_failed
// - customer.subscription.deleted
// - customer.subscription.updated

// Email Configuration (Optional)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');

// Brevo Configuration (Alternative to SMTP)
define('BREVO_API_KEY', 'your-brevo-api-key-here');

// Plugin Settings (Optional - can be set via admin panel)
define('KB_PREMIUM_DISCOUNT', 25); // Percentage discount for premium members
define('KB_MEMBERSHIP_PRICE', 35.00); // Monthly membership price in EUR
define('KB_WORKSHOP_CURRENCY', 'EUR'); // Default currency for workshops

/**
 * IMPORTANT SECURITY NOTES:
 * 
 * 1. Never commit your actual API keys to version control
 * 2. Use environment variables in production
 * 3. Keep your secret keys secure and private
 * 4. Use test keys for development
 * 5. Enable SSL on your production site (required by Stripe)
 * 
 * For production, consider using environment variables:
 * 
 * define('STRIPE_TEST_PUBLISHABLE_KEY', $_ENV['STRIPE_TEST_PUBLISHABLE_KEY']);
 * define('STRIPE_TEST_SECRET_KEY', $_ENV['STRIPE_TEST_SECRET_KEY']);
 * define('STRIPE_LIVE_PUBLISHABLE_KEY', $_ENV['STRIPE_LIVE_PUBLISHABLE_KEY']);
 * define('STRIPE_LIVE_SECRET_KEY', $_ENV['STRIPE_LIVE_SECRET_KEY']);
 */

/**
 * WEBHOOK SETUP INSTRUCTIONS:
 * 
 * 1. Go to your Stripe Dashboard
 * 2. Navigate to Developers > Webhooks
 * 3. Click "Add endpoint"
 * 4. Enter your webhook URL: https://yourdomain.com/stripe-webhook
 * 5. Select these events:
 *    - checkout.session.completed
 *    - invoice.payment_succeeded
 *    - invoice.payment_failed
 *    - customer.subscription.deleted
 *    - customer.subscription.updated
 * 6. Click "Add endpoint"
 * 7. Copy the webhook signing secret and add it to your plugin settings
 */

/**
 * TESTING CHECKLIST:
 * 
 * ✅ Stripe test mode enabled
 * ✅ Test API keys configured
 * ✅ Webhook endpoint configured
 * ✅ SSL certificate installed (for production)
 * ✅ Plugin settings configured
 * ✅ Test payment processed successfully
 * ✅ Email notifications working
 * ✅ Database tables created
 * ✅ User roles and capabilities set
 */

/**
 * PRODUCTION CHECKLIST:
 * 
 * ✅ Stripe live mode enabled
 * ✅ Live API keys configured
 * ✅ Webhook endpoint configured with live keys
 * ✅ SSL certificate installed and working
 * ✅ All test payments successful
 * ✅ Email delivery confirmed
 * ✅ Database backup created
 * ✅ Monitoring and logging enabled
 */ 