<?php
App::import('Core', array('Security', 'String'));
class FlourShell extends Shell
{
/**
 * Contains tasks to load and instantiate
 *
 * @var array
 * @access public
 */
	var $tasks = array(
		'DbConfig',
		'ProgressBar',
		'SqlFile',
		'Install',
		'Prepare',
		'Todo',
	);

	var $methods = array(
		'help',
	);

	function startup()
	{
		if(!file_exists(ROOT.DS.'app') && file_exists(ROOT.DS.'web'))
		{
			$this->params['app'] = 'web';
			$this->params['working'] = str_replace('app', 'web', $this->params['working']);
			$this->params['webroot'] = 'content';
		}
		$this->_welcome();
	}

	function main()
	{
		
		$method = array_shift($this->args);
		if(empty($method)) $method = 'help';
		if(in_array($method, $this->methods))
		{
			$this->$method();
		} else {
			$this->help();
		}
	}

	function help()
	{
		$this->out('');
		$this->out('  Adds flour to your cake.');
		$this->hr();
		$this->out('  available tasks: ');
		$this->out('');
		$this->out('  - install      installs all relevant tables');
		$this->out('  - prepare      copies needed files from flour/templates to app/');
		$this->out('  - help         prints the help you are looking at, right now.');
		$this->out('');
	}

	protected function __checkConnection()
	{
		if (!config('database')) {
			$this->out(__("Your database configuration was not found. Take a moment to create one.", true));
			$this->args = null;
			return $this->DbConfig->execute();
		}
	}
}
?>