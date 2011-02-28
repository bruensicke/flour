# Installation of Flour

You can load up Flour with this code (e.g. in your `bootstrap.php`):

	App::import('Lib', 'Flour.init');

**Notice**: You do not need this import, if you are using any Helper or Component, provided by Flour.

Also, to setup all database-tables run the following from a shell:

	cake flour install
	
This will populate all necessary tables, prefixed with `flour_` in your database.