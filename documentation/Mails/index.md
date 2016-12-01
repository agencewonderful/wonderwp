# Working with emails

## Proposal

- Working with email objects
- Being able to install new mailers easily
- Being able to switch mailers easily

The ability to chose the mail you'd like to use leads the way to the dependency injection of a Mailer Interface

## Objects at disposal

- a Mailer Interface (`WonderWp\Mail\MailerInterface`)
- an Abstract Mailer with default getters, setters, and so on. (`WonderWp\Mail\AbstractMailer`)
- A functionnal mailer class that works with the defautl wp_mail function (`WonderWp\Mail\WpMailer`)
- There's also a gateways folder inside which we'll put classes that link this mailer mechanism with third parts services (Mandrill, Swiftmailer...) because remember that the idea is to interact with a class that implements the MailerInterface so we can switch the mailer easily. And those third part services have their own methods and inner workings, so we put a gateway class in between to map the WonderWp mechanism with theirs.