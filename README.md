OsLab security API bundle
========================
> Authentification API REST

[![Build Status](https://travis-ci.org/OsLab/security-api-bundle.svg?branch=master)](https://travis-ci.org/OsLab/security-api-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OsLab/security-api-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/OsLab/security-api-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/OsLab/security-api-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/OsLab/security-api-bundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/OsLab/security-api-bundle/downloads)](https://packagist.org/packages/OsLab/security-api-bundle)
[![Latest Stable Version](https://poser.pugx.org/OsLab/security-api-bundle/v/stable)](https://packagist.org/packages/OsLab/security-api-bundle)
[![License](https://poser.pugx.org/OsLab/security-api-bundle/license)](https://packagist.org/packages/OsLab/SupervisorBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/21afc65a-16de-463c-9897-e3deb06ac615/mini.png)](https://insight.sensiolabs.com/projects/21afc65a-16de-463c-9897-e3deb06ac615)

Introduction
-------------
This bundle allows you to add an authentication mechanism with a token easily to your APIs.

Once you've configured everything, you'll be able to authenticate by adding an apikey parameter to the query string, like http://example.com/api/key?apikey=513e45b56f637b51d194a7524f2d51f2.
Or add through a header your token.

Installation
------------

### Step 1: Download OsLabSlimMonolog using [Composer](http://getcomposer.org)

Require the bundle with composer:

    $ composer require oslab/security-api-bundle

Or you can add it in the composer.json. Just check Packagist for the version you want to install (in the following example, we used "dev-master") and add it to your composer.json:

```json
    {
        "require": {
            "oslab/security-api-bundle": "dev-master"
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
            
        ...
        
    access_control:
        ...
        - { path: ^/api/*, roles: [ROLE_API]}
        ...
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

```
    Resources/meta/LICENSE
```

## Credits

* [All contributors](https://github.com/OsLab/slim-monolog/graphs/contributors)

## License

OsLabSecurityApiBundle is released under the MIT License. See the bundled LICENSE file for details.
