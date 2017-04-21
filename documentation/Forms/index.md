# Forms

## Needs
- Interact with form objects, not HTML
- Form validation rules
- Be able to create different field types to add them easily

## Proposal

### Build
1. Symfony form component? Too heavy?
2. One class form, that has form fields, that is rendered by a view.

### Validation
- Creation of a validation class with several methods
- for each field, specify which methods to call. All called methods need to return true for the field to be considered valid

### Available objects

- Form : the form instance that will hold th different fields
- FormValidator : the object that can validate a form instance versus a set of given data
- FormView : the object that will render the form HTML
- All the form fields classes : the fields instances tha twill be kept by the form instance

## How to create a form

1. Get a form instance from the dependency injection container
2. Add fields. That means instanciating classes that implement the `FieldInterface` interface. you can find the complete list of available Fields in the Forms/Fields folder.


```
$container = Container::getInstance();
/** @var Form $form */
$form = $container->offsetGet('wwp.forms.form'); //Get form instance
$form->setName('er-login-form'); //You can set a name if you want

//First Field
$f1 = new InputField('er-login',$loginVal,['label'=>__('username',TEXTDOMAIN)]);
$form->addField($f1);

//Second Field
$f2 = new PasswordField('er-pwd',$pwdVal,['label'=>__('password',TEXTDOMAIN)]);
$form->addField($f2);
```
Your form is ready

## How to render a form

This is the FormView job.

```
//Get FormView instance from DI container
$formView = $container->offsetGet('wwp.forms.formView');
//Assign form instance to it
$formView->setFormInstance($formInstance);
//Compute view
echo $formView->render();
```

## How to validate a form

### Specify validation rules

You can assing validation rules to each field. Validation rules is an array of rules that you can assign to the form field. The validation engine being used is https://github.com/Respect/Validation

```
$rules = [
	v::numericVal(),
	v::positive(),
	v::between(1, 255)
];
$fileld->setValidationRules($rules);
You can also pass them directly as the field constructor's fourth parameter
```

### On form submit

```
//Get FormValidator from DI container
$formValidator = $container->offsetGet('wwp.forms.formValidator');
//Indicate which form we'll validate
$formValidator->setFormInstance($form);
//This will take the given $data, loop through each form field, and validate it against the given data. You can also pass a textdomain for error messages
$errors = $formValidator->validate($data, $this->_textDomain);
```
