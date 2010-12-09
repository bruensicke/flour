<?php
/**
 * Content Model
 * 
 * Every kind of model can be an item in the content library.
 * The library itself can be queried through various ways, giving
 * access to every item regarding the state of editions.
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Content extends FlourAppModel
{

/**
 * Attached behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Flour.Polymorphic',
		'Flour.Taggable',
	);

/**
 * Validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'model' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

/**
 * constructor auto-sets $this->types to a merged set of:
 * 
 *   o  Flour.Content.types.options
 *   o  App.Content.types.options
 *
 * @access public
 */
	public function __construct()
	{
		$appTypes = (Configure::read('App.Content.types.options'))
			? Configure::read('App.Content.types.options')
			: array();

		$flourTypes = (Configure::read('Flour.Content.types.options'))
			? Configure::read('Flour.Content.types.options')
			: array();
		$this->types = array_merge($flourTypes, $appTypes);

		Configure::write('App.Content.types.options', $this->types);
		return parent::__construct();
	}

/**
 * gets the current ContentObject with given $slug
 *
 * @param string $slug slug of ContentObject to retrieve (or id)
 * @return mixed array of $data, if found in database for the currently active content - false otherwise
 * @access public
 */
	public function get($slug_or_id)
	{
		$field = (Validation::uuid($slug_or_id))
			? 'id'
			: 'slug';

		$data = $this->find('current', array($field => $slug_or_id));
		if(empty($data))
		{
			return false;
		}
		return $data;
	}
}
