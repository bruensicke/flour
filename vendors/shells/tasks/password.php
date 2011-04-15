<?php
/**
 * Flour PasswordTask
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
/**
 * Importing the security component,
 * which is used to hash the passwords
 */
App::import('Core', 'Security');
 
/**
 * Hashes passwords in the given table
 * Usage: "cake flour password" (to run against the default users table and password field)
 * Or: "cake flour password -table some_table -field another_field"
 *
 * Strict mode can be turned off, this way
 * any password even potentially a hashed one already will be re-hashed
 * see below for more info on how that works. From command line:
 * "cake flour password -strict false"
 * Or: "cake flour password -strict 0"
 *
 */
class PasswordTask extends FlourShell {
 

	/**
	 * Contains methods that exist directly here
	 *
	 * @var array
	 * @access public
	 */
	public $methods = array(
		'process',
		'reset',
		'help',
	);
 
	/**
	 * Holds the instance of the current model,
	 * where the hashing needs to happen
	 * (Defaults to User model)
	 *
	 * @var object Model
	 * @access private
	 */
	private $model = 'User';
 
	/**
	 * Field to hash, defaults to password
	 *
	 * @var string Field
	 * @access private
	 */
	private $field = 'password';
 
	/**
	 * If the strict (mode) is set to TRUE
	 * the shell will ignore any 40 char passwords
	 * (40 chars is the default length of cake's hashed password)
	 *
	 * From the shell we can specify the strict mode as:
	 * cake password_hash -strict false/0
	 *
	 * @var boolean
	 * @access private
	 */
	private $strict = TRUE;
 
	/**
	 * Generic initialization function
	 *
	 * (Always a good idea to call the parent method)
	 *
	 * @access public
	 * @return void
	 */
	public function initialize() {
		parent::initialize();
	}
 
	/**
	 * Starting up the shell
	 * Initializing shell properties based on the passed in params,
	 * or falling back to defaults
	 *
	 * @access public
	 * @return void
	 */
	public function startup() {
		if (isset($this->params['silent']) && $this->params['silent']) {
			$this->silent = TRUE;
		}
 
		if (isset($this->params['table']) && $this->params['table']) {
			$modelName = Inflector::classify($this->params['table']);
			$this->model = ClassRegistry::init($modelName);
		} else {
			$this->model = ClassRegistry::init($this->model);
		}
 
		if (isset($this->params['field']) && $this->params['field']) {
			$this->field = $this->params['field'];
		}
 
		if (isset($this->params['all']) && $this->params['all']) {
				$this->strict = FALSE;
		}
	}

	/**
	 * Entry-Point for Task, dispatches Request
	 *
	 * defaults to 'process'
	 *
	 * @return void
	 * @access public
	 */
	public function execute() {
		$method = array_shift($this->args);
		if(empty($method)) $method = 'process';
		if(in_array($method, $this->methods))
		{
			$this->$method();
		} else {
			$this->help();
		}
	}
 
	/**
	 * Takes care of finding, hashing and saving the field
	 * (Uses standard CakePHP hashing method)
	 * If strict mode is enabled all 40 char passwords,
	 * which is default hashed length are ignored.
	 *
	 * @return void
	 * @access private
	 */
	private function process() {
		$conditions = 1;
		if($this->strict) {
			$conditions = array(
					'LENGTH(`' . $this->field . '`) <>' => 40
			);
		}
 
		$fieldsToHash = $this->model->find('all', array(
				'recursive' => -1,
				'fields' => array($this->model->primaryKey, $this->field),
				'conditions' => $conditions
		));
		
		if(empty($fieldsToHash)) {
			$this->out('no unhashed fields...');
		}
		
		foreach($fieldsToHash as $field) {
			$fieldValue = $field[$this->model->alias][$this->field];
			$recordId = $field[$this->model->alias][$this->model->primaryKey];
 
			$fieldValue = Security::hash($fieldValue, NULL, TRUE);
 
			$this->out('Changing password for: ' . $recordId);
			$this->out('Changed to: ' . $fieldValue);
 
			$this->model->create();
			$this->model->id = $recordId;
			$data = array(
				$this->model->alias => array(
					$this->field => $fieldValue
				)
			);
			$this->model->save($data, array(
				'validate' => FALSE,
				'callbacks' => FALSE
			));
		}
	}

	/**
	 * Resets password of given user
	 * List users, if none given
	 *
	 * @todo implement source
	 * @return void
	 * @access private
	 */
	private function reset() {
		$this->out('NOT YET IMPLEMENTED!', null, true);
	}

	/**
	 * Shell output
	 *
	 * @param string $string
	 * @param boolean $newline
	 * @return boolean
	 * @access public
	 */
	public function out($string, $newline = true, $force = false) {
		if($this->verbose || $force) {
			parent::out($string, $newline = true);
		}
		return false;
	}
 
	/**
	 * Displays help contents
	 *
	 * @access public
	 */
	public function help() {
		$this->out('usage: ', null, true);
		$this->out('cake flour password reset', null, true);
		$this->out('cake flour password process', null, true);
		$this->_stop();
	}
}