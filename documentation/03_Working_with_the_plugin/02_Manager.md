# The Plugin Manager

The manager is an essential file for your plugin. It's inside this file that you register your files towards the auto loader, that you register your controllers, your services, your plugin configuration.

## Autoloading your plugin files
### Needs
- Using Namespaces
- Avoid includes / requires...
- Specify dependencies

### Proposal
- Using composer
- Composer works with composer.json objects, that are parsed when running composer install|update
- Then it creates an autoloader file dynamically under vendor/autoload.php.

#### Proposed solution 1:

- A) If it's a multi project plugin, installable via composer: use a composer.json file at the root of this plugin.

```
{
    "name": "agencewonderful/wwp-actu",
    "description": "Plugin wwp-actu",
    "type": "wordpress-plugin",
    "minimum-stability": "dev",
    "prefer-stable": true,
    "authors": [
        {
            "name": "Jeremy Desvaux",
            "email": "jeremy.desvaux@wonderful.fr"
        }
    ],
    "require": {
        "agencewonderful/wonderwp-plugin-core": "^1.0.0@dev"
    },
    "autoload": {
        "psr-4": {
            "WonderWp\\Plugin\\Actu\\": "includes"
        }
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://won.wonderful.fr/satis/packages-mirror"
        }
    ]
}

```

- B) If it's a plugin developed locally within the project, add a dedicated autoload section within the main project's composer.json file.

```
    "autoload": {
        "psr-4": {
            "My\\Plugin\\Namespace\\": "my/plugin/includes/folder"
        }
    },
```

##Registering your configs

See `$this->setConfig($key,$val)` and `$this->getConfig($key,$defaultValueIfEmpty)`;

##Registering your controllers

```
//Admin controller example
$this->addController(AbstractManager::ADMIN_CONTROLLER_TYPE, function () {
    return new ErThemeAdminController($this);
});

//Public controller example
$this->addController(AbstractManager::PUBLIC_CONTROLLER_TYPE, function () {
    return new ErThemePublicController($this);
});
```

The two types are constants on the `AbstractManager` class


##Registering your services

```
$this->addService(ServiceInterface::HOOK_SERVICE_NAME, function () {
    //Hook service
    return new ErThemeHookService();
});
```

You can see the reserved service names as constants on the `ServiceInterface` class.
