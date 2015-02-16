# Hatchery Video Transcoding

[![Latest Stable Version](https://poser.pugx.org/issetbv/hatchery-api-client/version.png)](https://packagist.org/packages/issetbv/hatchery-api-client)
[![License](https://poser.pugx.org/issetbv/hatchery-api-client/license.png)](https://packagist.org/packages/issetbv/hatchery-api-client)
[![Total Downloads](https://poser.pugx.org/issetbv/hatchery-api-client/downloads.png)](https://packagist.org/packages/issetbv/hatchery-api-client)

This is the PHP SDK for Hatchery Video Transcoding API.

### Table of Contents

- [Quick and Dirty](#quick-and-dirty)
- [Requirements](#requirements)
- [Installation](#installation)
- [License](#license)

### Quick and Dirty

We're assuming you've installed this package through Composer and is autoload.

```php
use Hatchery\Payload\JobAdd;

$client = new Hatchery\Client(new CurlPost('api_url', 'api_key'));
$client->sendPayload(new JobAdd('preset', 'ftp-in', 'ftp-out'));
```

### Requirements

- PHP 5.3

### Installation

**Composer**

Run the following to include this via Composer

```shell
composer require issetbv/hatchery-api-client
```

### License

This package is licensed under the [MIT license](https://github.com/issetbv/hatchery-api-client/blob/master/LICENSE).