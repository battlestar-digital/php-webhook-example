# Freemius PHP Webhook Example
**This is a work in progress; do not use**

Forked from the [Freemius PHP Webhook Example](https://github.com/Freemius/php-webhook-example), which was built by [Freemius](https://github.com/Freemius) for MailChimp.
Super-simple webhook example that does two things:

1. Subscribes new plugin users to a ConvertKit mailing list.
2. Send post-uninstall custom emails to users based on different uninstall reasons.

# Requirements
* Freemius PHP SDK (https://github.com/Freemius/php-sdk)
* ConvertKit PHP SDK (https://github.com/ConvertKit/ConvertKitSDK-PHP)
* Emails sender (e.g. http://swiftmailer.org/)

# Configuration
1. Download the Freemius SDK and add it to the `freemius-sdk` directory
1. Download the ConvertKit SDK and add the contents of their `src` directory it to this repo's `convertkit-sdk` directory

# Setup
Currently, the developer's dashboard does not expose an option to add your webhook address. You would need to contact Freemius first at support@freemius.com.

# Testing
During development on localhost, your webhook will not be accessible from the Internet. Therefore, testing will not automatically work. 

1. You can examine the webhook calls with tools like [RequestBin](http://requestb.in/).
2. Or the recommended way is to use tunneling with services like [ngrok](https://ngrok.com/). This way you can use your IDE and debugger.
