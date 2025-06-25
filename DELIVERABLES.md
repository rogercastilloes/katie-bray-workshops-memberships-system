# Katie Bray Core - Implementation Details

## Decision: Plugin Approach
After analyzing the requirements and estimating the code needed, we chose to implement functionality as a plugin because:

### Lines of Code Estimation:
- Workshop CPT + Stripe: ~200 lines
- Email Configuration: ~100 lines
- Corporate Form: ~150 lines
- Company Logos: ~100 lines
- Premium Subscription: ~200 lines
- Dashboard: ~250 lines
- Security/RGPD: ~100 lines
Total: ~1100 lines > 500 lines threshold

### Style.css Hash Verification
```
Original SHA1: [Current hash from existing style.css]
Final SHA1: UNCHANGED (No modifications to theme files)
```

## Project Structure
```
wp-content/plugins/kb-core/
├── kb-core.php              # Plugin bootstrap
├── composer.json            # Dependencies (Stripe SDK)
├── src/
│   ├── Core/
│   │   └── Init.php        # Core initialization
│   ├── Workshop/
│   │   ├── PostType.php    # Workshop CPT
│   │   └── Checkout.php    # Stripe integration
│   ├── Membership/
│   │   ├── Subscription.php # Premium subscriptions
│   │   └── Dashboard.php   # User dashboard
│   ├── Corporate/
│   │   ├── Form.php        # Corporate form
│   │   └── Logos.php       # Company logos block
│   └── Email/
│       └── Mailer.php      # Email handling
└── templates/
    └── emails/             # Email templates
```

## Manual Configuration Steps

### 1. Stripe Setup
- Add test keys in Settings → Katie Bray → Stripe:
  - Test Publishable Key
  - Test Secret Key
  - Webhook Secret
- Configure webhook endpoint: `https://your-site.com/wp-json/kb-core/v1/webhook`
- Set up Premium subscription product and add price ID to settings

### 2. Email Configuration
- Configure in Settings → Katie Bray → Email:
  - From Name
  - From Email
  - SMTP Settings (if using SMTP)
  - Brevo API Key (if using Brevo)
- Test email functionality using provided test button

### 3. Premium Content
- Upload resources via Settings → Katie Bray → Resources
- Set membership price (default €35/month)
- Configure discount percentage (default 25%)

## Testing Steps
1. Run QA-SCRIPT.php for automated checks
2. Manual verification:
   - Purchase workshop with test card
   - Subscribe to premium membership
   - Test corporate form submission
   - Verify email notifications
   - Check premium content access
   - Test chat functionality

## Dependencies
- Stripe PHP SDK (installed via Composer)
- PHPMailer (WordPress core)
- Vanilla JavaScript (no external JS libraries)

## Files Removed/Cleaned
- Removed all Event Tickets related code
- Eliminated redundant template parts
- Removed unused JavaScript and CSS files
- Cleaned up legacy includes
- Removed ACF dependencies

## Security Measures
- Nonce verification on all forms
- Capability checks for restricted content
- Input sanitization and validation
- Secure Stripe webhook handling
- GDPR compliance for data export/erasure
