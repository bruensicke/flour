<?php
switch(true)
{
	case Configure::read('App.Settings.name') != '':
		break;

	case APP_DIR != 'app':
		Configure::write('App.Settings.name', APP_DIR);
		break;

	default:
		$rootArray = pathinfo(ROOT);
		Configure::write('App.Settings.name', $rootArray['basename']);
}

switch(true)
{
	case Configure::read('App.Settings.version') != '':
		break;
	default:
		Configure::write('App.Settings.version', '0.1');
}

switch(true)
{
	case Configure::read('App.Settings.title') != '':
		break;
	default:
		Configure::write(':title - :name (:version)');
}

