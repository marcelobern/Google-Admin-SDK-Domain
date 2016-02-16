# Google-Admin-SDK-Domain
The steps described here can be used to change the primary domain of your Google for Work (Google Apps) account using the Google Admin SDK.

This work is based on the [Google Directory API (Admin SDK) PHP Quickstart](https://developers.google.com/admin-sdk/directory/v1/quickstart/php)

##Important - Read Before Using This Code
Before using these steps to change your primary domain name, you should read the following articles:
* [Changing your primary domain](https://support.google.com/a/answer/54819?hl=en)
* \<Key Article - read before proceeding!!!\> [Before you change your primary domain](https://support.google.com/a/answer/6301932)
* [Google Directory API - Changing a customer's primary domain name](https://developers.google.com/admin-sdk/directory/v1/guides/manage-customers#changing_a_customers_primary_domain_name)

##Pre-requisites
The following steps whould be performed prior to running the PHP script:
* Follow [step 1 of the PHP Quickstart](https://developers.google.com/admin-sdk/directory/v1/quickstart/php#step_1_turn_on_the_api_name) to turn on the Directory API for your application.
* Move to the app directory so all files are in the correct location:
```
cd app
```
* Download and install [Composer](http://www.getcomposer.org):
```
php -r "readfile('https://getcomposer.org/installer');" | php
```
* Download the necessary Google API files:
```
php composer.phar require google/apiclient:1.*
```

##Changing Your Primary Domain
This section paraphrases [step 4 of the PHP Quickstart](https://developers.google.com/admin-sdk/directory/v1/quickstart/php#step_4_run_the_sample)

In order to change the primary domain, you will need to:
* Change the line 123 of primary-domain-change.php by replacing NEW_PRIMARY_DOMAIN with your desired new primary domain:
```
// NEW_PRIMARY_DOMAIN must be a verified secondary domain for the Google for Work (Google App) account.
$customer->setCustomerDomain("NEW_PRIMARY_DOMAIN");
```
* \<Important!!!\> As indicated by the comment line above, NEW_PRIMARY_DOMAIN can only be a verified secondary domain for the Google for Work (Google App) account.
* Execute the following command:
```
php primary-domain-change.php
```

The first time you run the PHP code, it will prompt you to authorize access by displaying the following message:
```
Open the following link in your browser: <URL>
```
* Browse to the provided URL in your web browser. If you are not already logged into your Google account, you will be prompted to log in. If you are logged into multiple Google accounts, you will be asked to select one account to use for the authorization.
* Click the Accept button.
* Copy the code you're given, paste it into the command-line prompt, and press Enter.

You will see something similar to the following:
```
Before change - Google for Work (Google App) customer account information:
 creation time = 2016-02-16T19:22:375Z
 customer domain = my-primary-domain.com
 etag = "very-long-string-within-quotes"
 id = short-id-no-quotes
 language = en
 phone = +1234567890
After change - Google for Work (Google App) customer account information:
 creation time = 2016-02-16T19:22:375Z
 customer domain = my-new-primary-domain.com
 etag = "same-very-long-string-within-quotes"
 id = same-short-id-no-quotes
 language = en
 phone = +1234567890
 ```
 
 Please note that only the "customer domain" should have changed to match your new desired customer domain.

##Additional Information
In case you are new to Google APIs, you may find the following tools to be useful:
* [Google APIs Explorer](https://developers.google.com/apis-explorer/)
* [Google Admin API discovery](https://developers.google.com/apis-explorer/?hl=en_US#s/discovery/v1/discovery.apis.getRest?api=admin&version=directory_v1&_h=1&)

If you need to review the Google code for the Directory API, you can find it in the following path:
```
./app/vendor/google/apiclient/src/Google/Service/Directory.php
```
