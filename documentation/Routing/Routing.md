#Routing 

## Needs

### Backend
- In the WordPress admin URLs are not rewritten.
- When creating a plugin, you add a Main menu or Submenu page to the admin menu. This gives you an entry point.
- From there, for a CRUD, we'll need a list view and an edit view.
- Which URL format should we use? Use the entry point to root via a method? To launch a Router? Where are the routes defined (keeping in mind they are not rewritten)?

### Frontend
- Should we interact with the WP permalinks system? Or use external rules generation to add rules to .htaccess?
- Which URL format should we use? Which entry point? To launch a Router? Where are the routes defined?

#### External rules
- Sometimes you could need external rules. Should we facilitate this?

## Proposal

- Create a route service for your plugin. See the dedicated doc section : wonderwp framework > Services > Route service
- Add routes in the service's getRoutes method
- Routes can map a url to a certain file, or map a url to a certain callable
- Routes can use wildcards

### Defining a route
Defining a route that maps a url to a file

```
$route = [
'(.*)/paramname/{wildcard}/pageno/{pageno}', //Url definition
'index.php?pagename=$matches[1]&paramname=$matches[2]&pageno=$matches[3]', //File definition
'GET' //Authorized HTTP method
], //example of a route that maps a url to a file
```

Defining a route that maps a url to a callable

```
$route = [
'recette/resetfilters/{previousRecettePageId}', //Url definition
array($myObj,'myObjMethod'), //Callabledefinition
'GET' //Authorized HTTP method
] //example of a route that maps a url to a callable
```