<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3d2fc9df17c0680384c40c4ccc9291ad
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
        'K' => 
        array (
            'KatieBray\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'KatieBray\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Stripe\\Account' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Account.php',
        'Stripe\\AccountLink' => __DIR__ . '/..' . '/stripe/stripe-php/lib/AccountLink.php',
        'Stripe\\ApiOperations\\All' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/All.php',
        'Stripe\\ApiOperations\\Create' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/Create.php',
        'Stripe\\ApiOperations\\Delete' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/Delete.php',
        'Stripe\\ApiOperations\\NestedResource' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/NestedResource.php',
        'Stripe\\ApiOperations\\Request' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/Request.php',
        'Stripe\\ApiOperations\\Retrieve' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/Retrieve.php',
        'Stripe\\ApiOperations\\Search' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/Search.php',
        'Stripe\\ApiOperations\\SingletonRetrieve' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/SingletonRetrieve.php',
        'Stripe\\ApiOperations\\Update' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiOperations/Update.php',
        'Stripe\\ApiRequestor' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiRequestor.php',
        'Stripe\\ApiResource' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiResource.php',
        'Stripe\\ApiResponse' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApiResponse.php',
        'Stripe\\ApplePayDomain' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApplePayDomain.php',
        'Stripe\\ApplicationFee' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApplicationFee.php',
        'Stripe\\ApplicationFeeRefund' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ApplicationFeeRefund.php',
        'Stripe\\Apps\\Secret' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Apps/Secret.php',
        'Stripe\\Balance' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Balance.php',
        'Stripe\\BalanceTransaction' => __DIR__ . '/..' . '/stripe/stripe-php/lib/BalanceTransaction.php',
        'Stripe\\BankAccount' => __DIR__ . '/..' . '/stripe/stripe-php/lib/BankAccount.php',
        'Stripe\\BaseStripeClient' => __DIR__ . '/..' . '/stripe/stripe-php/lib/BaseStripeClient.php',
        'Stripe\\BaseStripeClientInterface' => __DIR__ . '/..' . '/stripe/stripe-php/lib/BaseStripeClientInterface.php',
        'Stripe\\BillingPortal\\Configuration' => __DIR__ . '/..' . '/stripe/stripe-php/lib/BillingPortal/Configuration.php',
        'Stripe\\BillingPortal\\Session' => __DIR__ . '/..' . '/stripe/stripe-php/lib/BillingPortal/Session.php',
        'Stripe\\Capability' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Capability.php',
        'Stripe\\Card' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Card.php',
        'Stripe\\CashBalance' => __DIR__ . '/..' . '/stripe/stripe-php/lib/CashBalance.php',
        'Stripe\\Charge' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Charge.php',
        'Stripe\\Checkout\\Session' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Checkout/Session.php',
        'Stripe\\Collection' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Collection.php',
        'Stripe\\CountrySpec' => __DIR__ . '/..' . '/stripe/stripe-php/lib/CountrySpec.php',
        'Stripe\\Coupon' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Coupon.php',
        'Stripe\\CreditNote' => __DIR__ . '/..' . '/stripe/stripe-php/lib/CreditNote.php',
        'Stripe\\CreditNoteLineItem' => __DIR__ . '/..' . '/stripe/stripe-php/lib/CreditNoteLineItem.php',
        'Stripe\\Customer' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Customer.php',
        'Stripe\\CustomerBalanceTransaction' => __DIR__ . '/..' . '/stripe/stripe-php/lib/CustomerBalanceTransaction.php',
        'Stripe\\CustomerCashBalanceTransaction' => __DIR__ . '/..' . '/stripe/stripe-php/lib/CustomerCashBalanceTransaction.php',
        'Stripe\\Discount' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Discount.php',
        'Stripe\\Dispute' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Dispute.php',
        'Stripe\\EphemeralKey' => __DIR__ . '/..' . '/stripe/stripe-php/lib/EphemeralKey.php',
        'Stripe\\ErrorObject' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ErrorObject.php',
        'Stripe\\Event' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Event.php',
        'Stripe\\Exception\\ApiConnectionException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/ApiConnectionException.php',
        'Stripe\\Exception\\ApiErrorException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/ApiErrorException.php',
        'Stripe\\Exception\\AuthenticationException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/AuthenticationException.php',
        'Stripe\\Exception\\BadMethodCallException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/BadMethodCallException.php',
        'Stripe\\Exception\\CardException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/CardException.php',
        'Stripe\\Exception\\ExceptionInterface' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/ExceptionInterface.php',
        'Stripe\\Exception\\IdempotencyException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/IdempotencyException.php',
        'Stripe\\Exception\\InvalidArgumentException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/InvalidArgumentException.php',
        'Stripe\\Exception\\InvalidRequestException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/InvalidRequestException.php',
        'Stripe\\Exception\\OAuth\\ExceptionInterface' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/ExceptionInterface.php',
        'Stripe\\Exception\\OAuth\\InvalidClientException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/InvalidClientException.php',
        'Stripe\\Exception\\OAuth\\InvalidGrantException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/InvalidGrantException.php',
        'Stripe\\Exception\\OAuth\\InvalidRequestException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/InvalidRequestException.php',
        'Stripe\\Exception\\OAuth\\InvalidScopeException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/InvalidScopeException.php',
        'Stripe\\Exception\\OAuth\\OAuthErrorException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/OAuthErrorException.php',
        'Stripe\\Exception\\OAuth\\UnknownOAuthErrorException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/UnknownOAuthErrorException.php',
        'Stripe\\Exception\\OAuth\\UnsupportedGrantTypeException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/UnsupportedGrantTypeException.php',
        'Stripe\\Exception\\OAuth\\UnsupportedResponseTypeException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/OAuth/UnsupportedResponseTypeException.php',
        'Stripe\\Exception\\PermissionException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/PermissionException.php',
        'Stripe\\Exception\\RateLimitException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/RateLimitException.php',
        'Stripe\\Exception\\SignatureVerificationException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/SignatureVerificationException.php',
        'Stripe\\Exception\\UnexpectedValueException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/UnexpectedValueException.php',
        'Stripe\\Exception\\UnknownApiErrorException' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Exception/UnknownApiErrorException.php',
        'Stripe\\ExchangeRate' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ExchangeRate.php',
        'Stripe\\File' => __DIR__ . '/..' . '/stripe/stripe-php/lib/File.php',
        'Stripe\\FileLink' => __DIR__ . '/..' . '/stripe/stripe-php/lib/FileLink.php',
        'Stripe\\FinancialConnections\\Account' => __DIR__ . '/..' . '/stripe/stripe-php/lib/FinancialConnections/Account.php',
        'Stripe\\FinancialConnections\\AccountOwner' => __DIR__ . '/..' . '/stripe/stripe-php/lib/FinancialConnections/AccountOwner.php',
        'Stripe\\FinancialConnections\\AccountOwnership' => __DIR__ . '/..' . '/stripe/stripe-php/lib/FinancialConnections/AccountOwnership.php',
        'Stripe\\FinancialConnections\\Session' => __DIR__ . '/..' . '/stripe/stripe-php/lib/FinancialConnections/Session.php',
        'Stripe\\FundingInstructions' => __DIR__ . '/..' . '/stripe/stripe-php/lib/FundingInstructions.php',
        'Stripe\\HttpClient\\ClientInterface' => __DIR__ . '/..' . '/stripe/stripe-php/lib/HttpClient/ClientInterface.php',
        'Stripe\\HttpClient\\CurlClient' => __DIR__ . '/..' . '/stripe/stripe-php/lib/HttpClient/CurlClient.php',
        'Stripe\\HttpClient\\StreamingClientInterface' => __DIR__ . '/..' . '/stripe/stripe-php/lib/HttpClient/StreamingClientInterface.php',
        'Stripe\\Identity\\VerificationReport' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Identity/VerificationReport.php',
        'Stripe\\Identity\\VerificationSession' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Identity/VerificationSession.php',
        'Stripe\\Invoice' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Invoice.php',
        'Stripe\\InvoiceItem' => __DIR__ . '/..' . '/stripe/stripe-php/lib/InvoiceItem.php',
        'Stripe\\InvoiceLineItem' => __DIR__ . '/..' . '/stripe/stripe-php/lib/InvoiceLineItem.php',
        'Stripe\\Issuing\\Authorization' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Issuing/Authorization.php',
        'Stripe\\Issuing\\Card' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Issuing/Card.php',
        'Stripe\\Issuing\\CardDetails' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Issuing/CardDetails.php',
        'Stripe\\Issuing\\Cardholder' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Issuing/Cardholder.php',
        'Stripe\\Issuing\\Dispute' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Issuing/Dispute.php',
        'Stripe\\Issuing\\Transaction' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Issuing/Transaction.php',
        'Stripe\\LineItem' => __DIR__ . '/..' . '/stripe/stripe-php/lib/LineItem.php',
        'Stripe\\LoginLink' => __DIR__ . '/..' . '/stripe/stripe-php/lib/LoginLink.php',
        'Stripe\\Mandate' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Mandate.php',
        'Stripe\\OAuth' => __DIR__ . '/..' . '/stripe/stripe-php/lib/OAuth.php',
        'Stripe\\OAuthErrorObject' => __DIR__ . '/..' . '/stripe/stripe-php/lib/OAuthErrorObject.php',
        'Stripe\\PaymentIntent' => __DIR__ . '/..' . '/stripe/stripe-php/lib/PaymentIntent.php',
        'Stripe\\PaymentLink' => __DIR__ . '/..' . '/stripe/stripe-php/lib/PaymentLink.php',
        'Stripe\\PaymentMethod' => __DIR__ . '/..' . '/stripe/stripe-php/lib/PaymentMethod.php',
        'Stripe\\Payout' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Payout.php',
        'Stripe\\Person' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Person.php',
        'Stripe\\Plan' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Plan.php',
        'Stripe\\Price' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Price.php',
        'Stripe\\Product' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Product.php',
        'Stripe\\PromotionCode' => __DIR__ . '/..' . '/stripe/stripe-php/lib/PromotionCode.php',
        'Stripe\\Quote' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Quote.php',
        'Stripe\\Radar\\EarlyFraudWarning' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Radar/EarlyFraudWarning.php',
        'Stripe\\Radar\\ValueList' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Radar/ValueList.php',
        'Stripe\\Radar\\ValueListItem' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Radar/ValueListItem.php',
        'Stripe\\RecipientTransfer' => __DIR__ . '/..' . '/stripe/stripe-php/lib/RecipientTransfer.php',
        'Stripe\\Refund' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Refund.php',
        'Stripe\\Reporting\\ReportRun' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Reporting/ReportRun.php',
        'Stripe\\Reporting\\ReportType' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Reporting/ReportType.php',
        'Stripe\\RequestTelemetry' => __DIR__ . '/..' . '/stripe/stripe-php/lib/RequestTelemetry.php',
        'Stripe\\Review' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Review.php',
        'Stripe\\SearchResult' => __DIR__ . '/..' . '/stripe/stripe-php/lib/SearchResult.php',
        'Stripe\\Service\\AbstractService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/AbstractService.php',
        'Stripe\\Service\\AbstractServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/AbstractServiceFactory.php',
        'Stripe\\Service\\AccountLinkService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/AccountLinkService.php',
        'Stripe\\Service\\AccountService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/AccountService.php',
        'Stripe\\Service\\ApplePayDomainService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/ApplePayDomainService.php',
        'Stripe\\Service\\ApplicationFeeService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/ApplicationFeeService.php',
        'Stripe\\Service\\Apps\\AppsServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Apps/AppsServiceFactory.php',
        'Stripe\\Service\\Apps\\SecretService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Apps/SecretService.php',
        'Stripe\\Service\\BalanceService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/BalanceService.php',
        'Stripe\\Service\\BalanceTransactionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/BalanceTransactionService.php',
        'Stripe\\Service\\BillingPortal\\BillingPortalServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/BillingPortal/BillingPortalServiceFactory.php',
        'Stripe\\Service\\BillingPortal\\ConfigurationService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/BillingPortal/ConfigurationService.php',
        'Stripe\\Service\\BillingPortal\\SessionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/BillingPortal/SessionService.php',
        'Stripe\\Service\\ChargeService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/ChargeService.php',
        'Stripe\\Service\\Checkout\\CheckoutServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Checkout/CheckoutServiceFactory.php',
        'Stripe\\Service\\Checkout\\SessionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Checkout/SessionService.php',
        'Stripe\\Service\\CoreServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/CoreServiceFactory.php',
        'Stripe\\Service\\CountrySpecService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/CountrySpecService.php',
        'Stripe\\Service\\CouponService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/CouponService.php',
        'Stripe\\Service\\CreditNoteService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/CreditNoteService.php',
        'Stripe\\Service\\CustomerService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/CustomerService.php',
        'Stripe\\Service\\DisputeService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/DisputeService.php',
        'Stripe\\Service\\EphemeralKeyService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/EphemeralKeyService.php',
        'Stripe\\Service\\EventService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/EventService.php',
        'Stripe\\Service\\ExchangeRateService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/ExchangeRateService.php',
        'Stripe\\Service\\FileLinkService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/FileLinkService.php',
        'Stripe\\Service\\FileService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/FileService.php',
        'Stripe\\Service\\FinancialConnections\\AccountService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/FinancialConnections/AccountService.php',
        'Stripe\\Service\\FinancialConnections\\FinancialConnectionsServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/FinancialConnections/FinancialConnectionsServiceFactory.php',
        'Stripe\\Service\\FinancialConnections\\SessionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/FinancialConnections/SessionService.php',
        'Stripe\\Service\\Identity\\IdentityServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Identity/IdentityServiceFactory.php',
        'Stripe\\Service\\Identity\\VerificationReportService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Identity/VerificationReportService.php',
        'Stripe\\Service\\Identity\\VerificationSessionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Identity/VerificationSessionService.php',
        'Stripe\\Service\\InvoiceItemService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/InvoiceItemService.php',
        'Stripe\\Service\\InvoiceService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/InvoiceService.php',
        'Stripe\\Service\\Issuing\\AuthorizationService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Issuing/AuthorizationService.php',
        'Stripe\\Service\\Issuing\\CardService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Issuing/CardService.php',
        'Stripe\\Service\\Issuing\\CardholderService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Issuing/CardholderService.php',
        'Stripe\\Service\\Issuing\\DisputeService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Issuing/DisputeService.php',
        'Stripe\\Service\\Issuing\\IssuingServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Issuing/IssuingServiceFactory.php',
        'Stripe\\Service\\Issuing\\TransactionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Issuing/TransactionService.php',
        'Stripe\\Service\\MandateService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/MandateService.php',
        'Stripe\\Service\\OAuthService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/OAuthService.php',
        'Stripe\\Service\\PaymentIntentService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/PaymentIntentService.php',
        'Stripe\\Service\\PaymentLinkService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/PaymentLinkService.php',
        'Stripe\\Service\\PaymentMethodService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/PaymentMethodService.php',
        'Stripe\\Service\\PayoutService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/PayoutService.php',
        'Stripe\\Service\\PlanService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/PlanService.php',
        'Stripe\\Service\\PriceService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/PriceService.php',
        'Stripe\\Service\\ProductService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/ProductService.php',
        'Stripe\\Service\\PromotionCodeService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/PromotionCodeService.php',
        'Stripe\\Service\\QuoteService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/QuoteService.php',
        'Stripe\\Service\\Radar\\EarlyFraudWarningService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Radar/EarlyFraudWarningService.php',
        'Stripe\\Service\\Radar\\RadarServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Radar/RadarServiceFactory.php',
        'Stripe\\Service\\Radar\\ValueListItemService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Radar/ValueListItemService.php',
        'Stripe\\Service\\Radar\\ValueListService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Radar/ValueListService.php',
        'Stripe\\Service\\RefundService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/RefundService.php',
        'Stripe\\Service\\Reporting\\ReportRunService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Reporting/ReportRunService.php',
        'Stripe\\Service\\Reporting\\ReportTypeService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Reporting/ReportTypeService.php',
        'Stripe\\Service\\Reporting\\ReportingServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Reporting/ReportingServiceFactory.php',
        'Stripe\\Service\\ReviewService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/ReviewService.php',
        'Stripe\\Service\\SetupAttemptService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/SetupAttemptService.php',
        'Stripe\\Service\\SetupIntentService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/SetupIntentService.php',
        'Stripe\\Service\\ShippingRateService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/ShippingRateService.php',
        'Stripe\\Service\\Sigma\\ScheduledQueryRunService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Sigma/ScheduledQueryRunService.php',
        'Stripe\\Service\\Sigma\\SigmaServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Sigma/SigmaServiceFactory.php',
        'Stripe\\Service\\SourceService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/SourceService.php',
        'Stripe\\Service\\SubscriptionItemService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/SubscriptionItemService.php',
        'Stripe\\Service\\SubscriptionScheduleService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/SubscriptionScheduleService.php',
        'Stripe\\Service\\SubscriptionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/SubscriptionService.php',
        'Stripe\\Service\\TaxCodeService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TaxCodeService.php',
        'Stripe\\Service\\TaxRateService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TaxRateService.php',
        'Stripe\\Service\\Tax\\CalculationService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Tax/CalculationService.php',
        'Stripe\\Service\\Tax\\SettingsService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Tax/SettingsService.php',
        'Stripe\\Service\\Tax\\TaxServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Tax/TaxServiceFactory.php',
        'Stripe\\Service\\Tax\\TransactionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Tax/TransactionService.php',
        'Stripe\\Service\\Terminal\\ConfigurationService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Terminal/ConfigurationService.php',
        'Stripe\\Service\\Terminal\\ConnectionTokenService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Terminal/ConnectionTokenService.php',
        'Stripe\\Service\\Terminal\\LocationService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Terminal/LocationService.php',
        'Stripe\\Service\\Terminal\\ReaderService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Terminal/ReaderService.php',
        'Stripe\\Service\\Terminal\\TerminalServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Terminal/TerminalServiceFactory.php',
        'Stripe\\Service\\TestHelpers\\CustomerService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/CustomerService.php',
        'Stripe\\Service\\TestHelpers\\Issuing\\CardService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Issuing/CardService.php',
        'Stripe\\Service\\TestHelpers\\Issuing\\IssuingServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Issuing/IssuingServiceFactory.php',
        'Stripe\\Service\\TestHelpers\\RefundService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/RefundService.php',
        'Stripe\\Service\\TestHelpers\\Terminal\\ReaderService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Terminal/ReaderService.php',
        'Stripe\\Service\\TestHelpers\\Terminal\\TerminalServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Terminal/TerminalServiceFactory.php',
        'Stripe\\Service\\TestHelpers\\TestClockService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/TestClockService.php',
        'Stripe\\Service\\TestHelpers\\TestHelpersServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/TestHelpersServiceFactory.php',
        'Stripe\\Service\\TestHelpers\\Treasury\\InboundTransferService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Treasury/InboundTransferService.php',
        'Stripe\\Service\\TestHelpers\\Treasury\\OutboundPaymentService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Treasury/OutboundPaymentService.php',
        'Stripe\\Service\\TestHelpers\\Treasury\\OutboundTransferService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Treasury/OutboundTransferService.php',
        'Stripe\\Service\\TestHelpers\\Treasury\\ReceivedCreditService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Treasury/ReceivedCreditService.php',
        'Stripe\\Service\\TestHelpers\\Treasury\\ReceivedDebitService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Treasury/ReceivedDebitService.php',
        'Stripe\\Service\\TestHelpers\\Treasury\\TreasuryServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TestHelpers/Treasury/TreasuryServiceFactory.php',
        'Stripe\\Service\\TokenService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TokenService.php',
        'Stripe\\Service\\TopupService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TopupService.php',
        'Stripe\\Service\\TransferService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/TransferService.php',
        'Stripe\\Service\\Treasury\\CreditReversalService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/CreditReversalService.php',
        'Stripe\\Service\\Treasury\\DebitReversalService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/DebitReversalService.php',
        'Stripe\\Service\\Treasury\\FinancialAccountService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/FinancialAccountService.php',
        'Stripe\\Service\\Treasury\\InboundTransferService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/InboundTransferService.php',
        'Stripe\\Service\\Treasury\\OutboundPaymentService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/OutboundPaymentService.php',
        'Stripe\\Service\\Treasury\\OutboundTransferService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/OutboundTransferService.php',
        'Stripe\\Service\\Treasury\\ReceivedCreditService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/ReceivedCreditService.php',
        'Stripe\\Service\\Treasury\\ReceivedDebitService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/ReceivedDebitService.php',
        'Stripe\\Service\\Treasury\\TransactionEntryService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/TransactionEntryService.php',
        'Stripe\\Service\\Treasury\\TransactionService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/TransactionService.php',
        'Stripe\\Service\\Treasury\\TreasuryServiceFactory' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/Treasury/TreasuryServiceFactory.php',
        'Stripe\\Service\\WebhookEndpointService' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Service/WebhookEndpointService.php',
        'Stripe\\SetupAttempt' => __DIR__ . '/..' . '/stripe/stripe-php/lib/SetupAttempt.php',
        'Stripe\\SetupIntent' => __DIR__ . '/..' . '/stripe/stripe-php/lib/SetupIntent.php',
        'Stripe\\ShippingRate' => __DIR__ . '/..' . '/stripe/stripe-php/lib/ShippingRate.php',
        'Stripe\\Sigma\\ScheduledQueryRun' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Sigma/ScheduledQueryRun.php',
        'Stripe\\SingletonApiResource' => __DIR__ . '/..' . '/stripe/stripe-php/lib/SingletonApiResource.php',
        'Stripe\\Source' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Source.php',
        'Stripe\\SourceTransaction' => __DIR__ . '/..' . '/stripe/stripe-php/lib/SourceTransaction.php',
        'Stripe\\Stripe' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Stripe.php',
        'Stripe\\StripeClient' => __DIR__ . '/..' . '/stripe/stripe-php/lib/StripeClient.php',
        'Stripe\\StripeClientInterface' => __DIR__ . '/..' . '/stripe/stripe-php/lib/StripeClientInterface.php',
        'Stripe\\StripeObject' => __DIR__ . '/..' . '/stripe/stripe-php/lib/StripeObject.php',
        'Stripe\\StripeStreamingClientInterface' => __DIR__ . '/..' . '/stripe/stripe-php/lib/StripeStreamingClientInterface.php',
        'Stripe\\Subscription' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Subscription.php',
        'Stripe\\SubscriptionItem' => __DIR__ . '/..' . '/stripe/stripe-php/lib/SubscriptionItem.php',
        'Stripe\\SubscriptionSchedule' => __DIR__ . '/..' . '/stripe/stripe-php/lib/SubscriptionSchedule.php',
        'Stripe\\TaxCode' => __DIR__ . '/..' . '/stripe/stripe-php/lib/TaxCode.php',
        'Stripe\\TaxId' => __DIR__ . '/..' . '/stripe/stripe-php/lib/TaxId.php',
        'Stripe\\TaxRate' => __DIR__ . '/..' . '/stripe/stripe-php/lib/TaxRate.php',
        'Stripe\\Tax\\Calculation' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Tax/Calculation.php',
        'Stripe\\Tax\\CalculationLineItem' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Tax/CalculationLineItem.php',
        'Stripe\\Tax\\Settings' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Tax/Settings.php',
        'Stripe\\Tax\\Transaction' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Tax/Transaction.php',
        'Stripe\\Tax\\TransactionLineItem' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Tax/TransactionLineItem.php',
        'Stripe\\Terminal\\Configuration' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Terminal/Configuration.php',
        'Stripe\\Terminal\\ConnectionToken' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Terminal/ConnectionToken.php',
        'Stripe\\Terminal\\Location' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Terminal/Location.php',
        'Stripe\\Terminal\\Reader' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Terminal/Reader.php',
        'Stripe\\TestHelpers\\TestClock' => __DIR__ . '/..' . '/stripe/stripe-php/lib/TestHelpers/TestClock.php',
        'Stripe\\Token' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Token.php',
        'Stripe\\Topup' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Topup.php',
        'Stripe\\Transfer' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Transfer.php',
        'Stripe\\TransferReversal' => __DIR__ . '/..' . '/stripe/stripe-php/lib/TransferReversal.php',
        'Stripe\\Treasury\\CreditReversal' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/CreditReversal.php',
        'Stripe\\Treasury\\DebitReversal' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/DebitReversal.php',
        'Stripe\\Treasury\\FinancialAccount' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/FinancialAccount.php',
        'Stripe\\Treasury\\FinancialAccountFeatures' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/FinancialAccountFeatures.php',
        'Stripe\\Treasury\\InboundTransfer' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/InboundTransfer.php',
        'Stripe\\Treasury\\OutboundPayment' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/OutboundPayment.php',
        'Stripe\\Treasury\\OutboundTransfer' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/OutboundTransfer.php',
        'Stripe\\Treasury\\ReceivedCredit' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/ReceivedCredit.php',
        'Stripe\\Treasury\\ReceivedDebit' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/ReceivedDebit.php',
        'Stripe\\Treasury\\Transaction' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/Transaction.php',
        'Stripe\\Treasury\\TransactionEntry' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Treasury/TransactionEntry.php',
        'Stripe\\UsageRecord' => __DIR__ . '/..' . '/stripe/stripe-php/lib/UsageRecord.php',
        'Stripe\\UsageRecordSummary' => __DIR__ . '/..' . '/stripe/stripe-php/lib/UsageRecordSummary.php',
        'Stripe\\Util\\ApiVersion' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/ApiVersion.php',
        'Stripe\\Util\\CaseInsensitiveArray' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/CaseInsensitiveArray.php',
        'Stripe\\Util\\DefaultLogger' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/DefaultLogger.php',
        'Stripe\\Util\\LoggerInterface' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/LoggerInterface.php',
        'Stripe\\Util\\ObjectTypes' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/ObjectTypes.php',
        'Stripe\\Util\\RandomGenerator' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/RandomGenerator.php',
        'Stripe\\Util\\RequestOptions' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/RequestOptions.php',
        'Stripe\\Util\\Set' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/Set.php',
        'Stripe\\Util\\Util' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Util/Util.php',
        'Stripe\\Webhook' => __DIR__ . '/..' . '/stripe/stripe-php/lib/Webhook.php',
        'Stripe\\WebhookEndpoint' => __DIR__ . '/..' . '/stripe/stripe-php/lib/WebhookEndpoint.php',
        'Stripe\\WebhookSignature' => __DIR__ . '/..' . '/stripe/stripe-php/lib/WebhookSignature.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3d2fc9df17c0680384c40c4ccc9291ad::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3d2fc9df17c0680384c40c4ccc9291ad::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3d2fc9df17c0680384c40c4ccc9291ad::$classMap;

        }, null, ClassLoader::class);
    }
}
