#Hook Service

Potentially, in a large plugin, you could find all sorts of hooks at many different places. The idea to have a hook service is to have a central place where all hooks are stored cleanly.

This service is then responsible for registering all the plugin hooks, and then dispatch the hook execution to the required classes.

##How to create a hook service
Create a class that extends the `AbstractHookService` class. `AbstractHookService` implements the `HookServiceInterface`, therefore it requires that your hook service implements a `run()` method.

```
class MyPluginHookService extends AbstractHookService
{
	public function run(){
		//Register your hooks from there
	}
}
```

##Registering the hook service
Add these few lines inside your plugin manager

```
$this->addService(AbstractService::$HOOKSERVICENAME,$container->factory(function($c){
    //Hook service
    return new MyPluginHookService();
}));
```