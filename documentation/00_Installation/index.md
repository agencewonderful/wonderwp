# Installation

This is a composer package, you can therefore get it like this:

```
composer require wonderwp/framework
```

## Initialisation


Call the WonderWp Framework Loader

```
\WonderWp\Loader::getInstance();
```

That initializes the autoload, getting you access to everything the framework can do. You would typically put this in a must-use plugin.