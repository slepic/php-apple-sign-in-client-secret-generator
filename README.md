# Apple Signin Client Secret Generator for PHP

## Description
This package provides class that generates token derived from your private key using ES256 JWT algorithm. For more info check [useful links](#useful-links)

## Requirements

PHP >= 8.1

## Installation

Install the composer package:

```composer require slepic/apple-sign-in-client-secret-generator```

## Example Usage

```php
<?php

use Slepic\AppleSignInClientSecretGenerator\AppleSignInClientSecretGenerator;

$clientId = 'com.example.TESTAPP';
$teamId   = 'FOO123BAR456';
$keyId    = '654RAB321OOF';
$certPath = __DIR__ . '/certificate.p8';
$privateKey = \file_get_contents($certPath);

$clientSecret = new AppleSignInClientSecretGenerator();

echo $clientSecret->generate($clientId, $teamId, $keyId, $privateKey);
```

## Credit

The project was originally forked from: [kissdigital-com/apple-sign-in-client-secret-generator](https://github.com/kissdigital-com/apple-sign-in-client-secret-generator)

When migrating from kissdigital.com solution, you must:
* replace the class name
* pass your credentials to the `generate()` method instead of the class constructor
* extract the private key from the file by your own means and only pass its contents to the `generate()` method
