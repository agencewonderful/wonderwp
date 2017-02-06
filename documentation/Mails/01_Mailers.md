# Mailers

## I want to use the mail mechanism I chose

- Look inside the gateways foler to see if the gateway for the mail mechanism you'd like to use exists. If yes, good news, you'll need to specify it as the mailer of choice towards the dependency injecter.
- The chosen mailer is defined in the Loader and passed to the dependency Injection mechanism. You can change its value in your theme or your plugin.
- If there's no gateway for your mailer, you'll have to create it first, and then specify it as the mailer of choice towards the dependency injecter. Don't forget to send it to us so we can make it available to the community :)

To change the mailer, change the dependency injection value for the key `wwp.emails.mailer`:

```
//Emails
$container['wwp.emails.mailer'] = $container->factory(function(){
    return new WpMailer();
});
```

## How to create a gateway for my third part mail mechanism

- Create a class that extends the `AbstractMailer` class. This will make sure you implement the mailer interface.
- Override the methods you'd like to change.
- Register the class as the mailer in the dependency injection
- Et voilà
