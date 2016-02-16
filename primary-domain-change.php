<?php
// The PHP code in this file is based on https://developers.google.com/admin-sdk/directory/v1/quickstart/php

require __DIR__ . '/vendor/autoload.php';

define('APPLICATION_NAME', 'Directory API PHP Update Primary Domain');
define('CREDENTIALS_PATH', '~/.credentials/admin-directory_v1-php-update-domain.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
define('SCOPES', implode(' ', array(
  Google_Service_Directory::ADMIN_DIRECTORY_CUSTOMER)
));

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfigFile(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = file_get_contents($credentialsPath);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->authenticate($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, $accessToken);
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->refreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, $client->getAccessToken());
  }
  return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

/**
 * Outputs the Google for Work (Google App) customer account information to the CLI.
 * @param Google_Service_Directory_Customer $customer the customer object for which the information will be displayed
 */
function printCustomerInfo($customer) {
  if ($customer == null) {
    print "No customer found.\n";
  } else {
    print "Google for Work (Google App) customer account information:\n";
    printf(" creation time = %s \n customer domain = %s \n etag = %s \n id = %s \n language = %s \n phone = %s \n",
    $customer->getCustomerCreationTime(),
    $customer->getCustomerDomain(),
    $customer->getEtag(),
    $customer->getId(),
    $customer->getLanguage(),
    $customer->getPhoneNumber()
//    $customer->getAlternateEmail(),
//    $customer->getKind(),
//    $customer->getPostalAddress(),
   );
  }
}

// Get the API client and construct the service object.
$client = getClient();
try {
  $service = new Google_Service_Directory($client);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

// Get the customer object with current primary domain.
$optParamsGet = array(
//  Insert any optional parameters here for the GET method. Parameters should comply with the following format:
//  'customer' => 'my_customer',
);
try {
  $result1 = $service->customers->get('my_customer', $optParamsGet);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

// Print out the Google Account information prior to the change.
print "Before change - ";
printCustomerInfo($result1);

// Update customer object with new primary domain name.
$customer = new Google_Service_Directory_Customer();

// NEW_PRIMARY_DOMAIN must be a verified secondary domain for the Google for Work (Google App) account.
$customer->setCustomerDomain("NEW_PRIMARY_DOMAIN");

// Update the customer object with the new primary domain.
$optParamsPost = array(
//  Insert any optional parameters here for the POST method. Parameters should comply with the following format:
//  'customer' => 'my_customer',
);
try {
  $result2 = $service->customers->update('my_customer', $customer, $optParamsPost);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

// Print out the Google Account information after the change
print "After change - ";
printCustomerInfo($result2);
