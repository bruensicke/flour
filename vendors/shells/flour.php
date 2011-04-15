<?php
/**
 * Flour Shell
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
App::import('Core', array('Security', 'String'));
App::import('Lib', 'Flour.init');
class FlourShell extends Shell
{

	/**
	 * Contains tasks to load and instantiate
	 *
	 * @var array
	 * @access public
	 */
	public $tasks = array(
		'DbConfig',
		'ProgressBar',
		'SqlFile',
		'Install',
		'Password',
		'Prepare',
		'Todo',
	);

	/**
	 * Contains methods that exist directly here
	 *
	 * @var array
	 * @access public
	 */
	public $methods = array(
		'help',
	);

	/**
	 * If set to true, will output more information
	 *
	 * @var string
	 * @access public
	 */
	public $verbose = false;

	/**
	 * Constructor
	 *
	 * Makes sure, $this->verbose is set correctly
	 *
	 * @param string $dispatch 
	 * @return void
	 * @access public
	 */
	public function __construct(&$dispatch) {
		parent::__construct($dispatch);
		$this->verbose = (!empty($this->params['v']) || !empty($this->params['verbose']))
			? true
			: false;
	}

	function startup()
	{
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