OsLab security API bundle
========================
> A bundle for securing ReST api calls.

[![Build Status](https://travis-ci.org/OsLab/security-api-bundle.svg?branch=master)](https://travis-ci.org/OsLab/security-api-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OsLab/security-api-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/OsLab/security-api-bundle/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/OsLab/security-api-bundle/badge.svg)](https://coveralls.io/github/OsLab/security-api-bundle)
[![Total Downloads](https://poser.pugx.org/OsLab/security-api-bundle/downloads)](https://packagist.org/packages/OsLab/security-api-bundle)
[![Latest Stable Version](https://poser.pugx.org/OsLab/security-api-bundle/v/stable)](https://packagist.org/packages/OsLab/security-api-bundle)
[![License](https://poser.pugx.org/OsLab/security-api-bundle/license)](https://packagist.org/packages/OsLab/SupervisorBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/21afc65a-16de-463c-9897-e3deb06ac615/mini.png)](https://insight.sensiolabs.com/projects/21afc65a-16de-463c-9897-e3deb06ac615)

Introduction
-------------
This bundle allows you to add an authentication mechanism with a token easily to your APIs.

Once you've configured everything, you'll be able to authenticate by adding an key parameter to the query string, like http://example.com/api/users?key=1x4c40nwh96080gk70f7k5awz9k6tczqs3jr01z94849n 
or add through a header your token.

Installation
------------

### Step 1: Download OsLabSecurityApiBundle using [Composer](http://getcomposer.org)

Require the bundle with composer:

    $ composer require oslab/security-api-bundle:">1.0"

Or you can add it in the composer.json. Just check Packagist for the version you want to install (in the following example, we used "1.0") and add it to your composer.json:

```json
    {
        "require": {
            "oslab/security-api-bundle": ">1.0"
        }
    }
```

### Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

```php
    // app/AppKernel.php
    
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new OsLab\SecurityApiBundle\OsLabSecurityApiBundle(),
        );
    }
```

### Step 3: Configure your application's security.yml

```yml
    role_hierarchy:
        ROLE_API: ROLE_API

    ...

    providers:
        chain_provider:
            chain:
                providers: [api_provider]

        ...

        api_provider:
            memory_api:
                users:
                    micro_service_asset:
                        password: '1x4c40nwh96080gk70f7k5awz9k6tczqs3jr01z94849n'
                        roles: 'ROLE_API'
                    external_api_customer:
                        password: 'j6eef2w0689a6if50c365v2zq0c855ywgyt106j2b6q5h'
                        roles: 'ROLE_API'
        ...

    firewalls:
        ...

        api_secured:
            pattern: ^/api/*
            stateless: true
            simple_preauth:
                authenticator: oslab_security_api.security.authentication.authenticator
            provider: api_provider

        main:
            anonymous: ~

        ...
        
    access_control:
        ...
        - { path: ^/api/*, roles: [ROLE_API]}
        ...
```

## Credits

* [All contributors](https://github.com/OsLab/security-api-bundle/graphs/contributors)

## License

Security API bundle is released under the MIT License, you agree to license your code under the [MIT license](LICENSE)
