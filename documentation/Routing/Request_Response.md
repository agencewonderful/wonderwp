# Needs
- For the request part
    - Accessing $_GET, $_POST, $_REQUEST, $_FILES, $_SESSION, $_COOKIES elements securely
    - Parameters manipulation
    - Access current URL
- For the response part
    - Is there a particular need?

# Proposal

## Request

Should we introduce a Symfony like Request object?

Yes?
- Good practice
- Input security, no direct access to $_GET, post etc
- Could allow us to abstract parameters manipulation
- Could give us access to current page url as variable, to current page params as array
- Centralises and structures all these things under one object
- Imitate the HTTP Request mechanism

Should we use the symfony one or create a smaller one?

No?
- Overkill

### Let's try the symfony proof of concept

Used component: http://api.symfony.com/2.8/Symfony/Component/HttpFoundation/Request.html

But there's no method to access a created request instance
How to access the request object from anywhere?
Creation of a Singleton, get instance from singleton
`$request = Request::getInstance();
$test = $request->get('test'); //as you would do in symfony`

It seems the vendor version is a bit too heavy for what we want, it would be interesting to create a smaller middleware, part of the framework that only does what we need.

## Response

Should we introduce a Symfony like Response object?

Yes?
- Useful for APIs
- Controllers return always the same object
- Standardizes controller output

Yes as a reference you can use, but not mandatory for a controller to return it?

Should we use the symfony one or create a smaller one?

No?
- We don't reason in pages, but in page parts

The request response doesn't seem to be particularly relevant in the WordPress's context. We'll probably use some kind of response object from within APIs though, but that would be part of a different context, not this one.
