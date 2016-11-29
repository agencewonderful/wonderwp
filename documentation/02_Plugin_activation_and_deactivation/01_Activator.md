##Plugin Activator 

Create a class that extends `AbstractPluginActivator` or implements the `ActivatorInterface`

```
class RecetteActivator extends AbstractPluginActivator
{

    /**
     * Create table for entity
     */
    public function activate()
    {
    	//What's inside the activate method will be executed upon plugin activation
    	//Here you could for example create a table for your plugin, define options...
    	
    	//Here's an example of table creation
    	//Necessitate to extends AbstractPluginActivator
        $this->_createTable(RecetteEntity::class);      
    }
}
```
What's inside the activate method will be executed upon plugin activation
Here you could for example create a table for your plugin, define options...

Here's an example of table creation:
```
$this->_createTable(MyPluginEntity::class);      
```

Table creation is handled by the Doctrine ORM. You just need to pass an entity name to the `_createTable` method. Necessitate to extends `AbstractPluginActivator` to have access to this method.