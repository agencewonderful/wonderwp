# Model Form

## Needs
- Interact with form objects, not HTML
- Define forms corresponding to a particular entity
- Pass an entity to such form to edit it, or no model to create it
- Form validation rules
- Be able to create different field types to add them easily


## Proposal

### Build
1. Symfony form component? Too heavy?
2. One class form, that takes one entity, that loops through its fields to create the form fields, and then that is rendered by a view.

#### Implementation
The idea is that the entity (=model) should not have any logic regarding this form concept.
So if we want the form instance to automatically build the form fields from an entity, it needs to loop through its attributes.
That can be an issue when the object attributes are private. What are the solutions?

- get_object_vars? If Entity attributes are private, it returns nothing.
- a getAttributes method on the entity? If attributes are private, this method would need to be written on the entity itself because we can't abstract the code to a parent class that would not see it child's private attributes.
- A trait? That's not really the purpose of the trait because in our case, our trait would be compulsory.
- Impose attributes to be protected and not private? That would be too extreme in face of our philosophy to bring industrial code quality to WordPress.
- An interface implemented by the entity imposing to redefine the getAttributes method? That would ensure the method is in the right place, but still you'd copy paste the same code in every entity which is not right.
- Closure. Found this link, https://ocramius.github.io/blog/accessing-private-php-class-members-without-reflection/, tried the solution, that worked very nicely. You can see private properties and all, but you are limited to php attributes, with no access to annotations.
- Doctrine annotations. Uses reflection, probably slower, but you have access to much more information via doctrine like more precise type, length, nullable, columnName... Much more interesting in relation to the form subject. Let's use that.