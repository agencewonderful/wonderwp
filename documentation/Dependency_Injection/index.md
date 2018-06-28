# Dependency Injection

# Needs
- Injecting dependencies (Instances, factories, configs) within methods so they are not hard coded, and easily interchangeable by new instances without having to rewrite the end code.

# Proposal
Used component: [Pimple](http://pimple.sensiolabs.org/)

- We need to create a pimple container. But then how do we access it from anywhere in the app?
- Creation of a Singleton, get instance from singleton

```
use WonderWp\DI\Container;
$container = Container::getInstance();
```

It then works like pimple explains in its documentation:

```// define some services
$container['session_storage'] = function ($c) {
    return new SessionStorage('SESSION_ID');
};
// define some parameters
$container['cookie_name'] = 'SESSION_ID';
$container['session_storage_class'] = 'SessionStorage';
```

And so on.
