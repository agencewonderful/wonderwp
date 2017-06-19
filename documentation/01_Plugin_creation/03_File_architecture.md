# Plugin file architecture

This is a complete definition. Your plugin could have much less files (juste the includes and admin folders for example)

- admin //All related admin resources
	- css
		- my-plugin-admin.css
	- js
		- my-plugin-admin.js 
- includes //All the autoloaded classes
	-  Services
		- HookService.php
		- ...
   - Activator.php //Activation routine (table creation)
   - Deactivator.php //Deactivation routine (if any)
   - Manager.php //Main entry point
   - AdminController.php //Backend Controller
   - PublicController.php //FrontEnd Controller   
- languages //language files
    - myplugin.pot, .mo, .po
- public //All related public resources
	- css 
		- my-plugin-public.css
	- js
		- my-plugin-public.js  
- index.php //Silence is golden
- myplugin.php //Main plugin file
