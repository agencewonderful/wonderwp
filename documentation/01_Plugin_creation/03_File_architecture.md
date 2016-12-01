# Plugin file architecture

This is a complete definition. Your plugin could have much less files (juste the includes and admin folders for example)

- admin //All related admin resources
	- css
		- my-plugin-admin.css
	- js
		- my-plugin-admin.js 
    - AdminController.php //Backend Controller
- includes //All the autoloaded classes
    - Activator.php //Activation routine (table creation)
    - Deactivator.php //Deactivation routine (if any)
    - Manager.php //Main entry point
    - (Entity.php) //Entity definition (if any - for CRUD plugins mostly)
    - (ListTable.php) //List Table (if any - for CRUD plugins mostly    
    - (Form.php) //Form definition (if any - for CRUD plugins mostly)
- languages //language files
    - myplugin.pot, .mo, .po
- public //All related public resources
	- css 
		- my-plugin-public.css
	- js
		- my-plugin-public.js  
    - PublicController.php //FrontEnd Controller
- index.php //Silence is golden
- myplugin.php //Main plugin file