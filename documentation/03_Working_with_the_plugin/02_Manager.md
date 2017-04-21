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

- The problem with this is that when you install a new theme or plugin, you can do it via the admin interface, and it might not get through the composer routine again. That's why we looked for a way to interact with the loader.

#### Proposed solution 1:
- Get autoLoader from dependency injection.
- Interact with the loader public methods like `addPsr4` for example to add some autoloading logic

Example:

```
public function autoLoad(AutoLoader $loader){

        $pluginDir = plugin_dir_path( dirname( __FILE__ ) );
        $loader->addPsr4('MyPlugin\\',array(
            $pluginDir . 'includes'
        ));
        $loader->addClassMap(array(
            'MyPlugin\\MyPluginAdminController'=>$pluginDir.'admin'.DIRECTORY_SEPARATOR.'MyPluginAdminController.php',
            'MyPlugin\\MyPluginPublicController'=>$pluginDir.'public'.DIRECTORY_SEPARATOR.'MyPluginPublicController.php'
        ));

	}
```

While this sounded interesting, it might be better to add a composer.json file at the root of the plugin? File that would be read by an autoload method, not in the plugin directly, but in its parent. This way, if the autoload routine changes, we wouldn't have to go back to our plugins and change every autoload method. But I'm not sure that's practical with plugin dir paths. We'll see.
