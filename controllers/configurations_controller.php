<?php
/**
 * ConfigurationsController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class ConfigurationsController extends FlourAppController
{

/**
 * which models to load
 *
 * @var string $uses
 * @access public
 */
	public $uses = array('Flour.Configuration');

/**
 * controls pagination
 *
 * @var array $paginate
 * @access public
 */
	public $paginate = array(
		'Configuration' => array(
			'limit' => 100,
		)
	);

/**
 * lists all available configurations, accepts a variety of named params
 *
 * {{{
 *	/contents/index/view:short #show all items with template 'short' (from item.ctp)
 *	/contents/index/search:foo #find all items with 'foo'
 *	/contents/index/tags:bar #find all items tagged 'bar'
 * }}}
 *
 * @return void
 * @access public
 */
	public function admin_index()
	{
		$conditions = $this->Search->buildConditions('Configuration');
		$order = 'Configuration.category ASC';
		$this->data = $this->Configuration->find('all', compact('conditions', 'order'));
		$this->set('tags', 
			$this->Configuration->Tagged->find('cloud', array(
				'model' => 'Configuration',
				'order' => 'Tag.name ASC',
			)));
	}

/**
 * View action
 *
 * @param integer $id ID of record
 * @return void
 * @access public
 */	
	public function admin_view($id = null)
	{
		if (!$id)
		{
			return $this->Flash->error(
				__('Invalid Configuration.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Configuration->read(null, $id);
	}

/**
 * add action
 * Handles several named params for itself, like
 * 
 * {{{
 *	/contents/add/type:foo #content of type 'foo'
 *	/contents/add/status:2 #set status to 2
 *	/contents/add/name:foo #pre-fill name with foo
 *	/contents/add/slug:bar #pre-fill slug with bar
 *	/contents/add/tags:baz #pre-fill tags with baz
 *	/contents/add/name:foo/slug:bar/type:baz #pre-fill all fields accordingly
 * }}}
 *
 * @return void
 * @access public
 */	
	public function admin_add()
	{
		if(!empty($this->data))
		{
			$this->Configuration->create($this->data);
			if($this->Configuration->validates())
			{
				if($this->Configuration->save(null, false))
				{
					$id = $this->Configuration->getInsertID();
					$this->Flash->success(
						__('Configuration :Configuration.name saved.', true),
						array('action' => 'edit', $id)
					);
				} else {
					$this->Flash->error(
						__('Configuration :Configuration.name could not be saved.', true)
					);
				}
			} else {
				//validation errors
			}
		}
	}

/**
 * edit action
 *
 * @param integer $id ID of record
 * @return void
 * @access public
 */	
	public function admin_edit($id = null)
	{
		if (!$id)
		{
			return $this->Flash->error(
				__('Invalid Configuration.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		if(!empty($this->data))
		{
			$this->Configuration->create($this->data);
			if($this->Configuration->validates())
			{
				if($this->Configuration->save(null, false))
				{
					$this->Flash->success(
						__('Configuration :Configuration.name saved.', true),
						array('action' => 'edit', $id)
					);
				} else {
					$this->Flash->error(
						__('Configuration :Configuration.name could not be saved.', true)
					);
				}
			} else {
				//validation errors
			}
		}
		$this->data = $this->Configuration->read(null, $id);
		$this->set('type', $this->data['Configuration']['type']);
		$this->set('editions', $this->Configuration->find('editions'));
	}

/**
 * delete action
 *
 * @return void
 * @access public
 */	
	public function admin_delete($id = null)
	{
		if (!$id)
		{
			$this->Flash->error(
				__('Invalid Configuration.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Configuration->read(null, $id);
		$result = $this->Configuration->delete($id);
		if(!$result)
		{
			return $this->Flash->error(
				__('Configuration :Configuration.name could not be deleted.', true),
				$this->referer(array('action'=>'index'))
			);
		}
		$this->Flash->success(
			__('Configuration :Configuration.name successfully deleted.', true),
			$this->referer(array('action'=>'index'))
		);
	}

/**
 * type renders the correct form-element as given in named param $type
 *
 * @return void
 * @access public
 */	
	public function admin_type()
	{

		//which type to render
		$type = (isset($this->passedArgs['type']))
			? $this->passedArgs['type']
			: Configure::read('Flour.Content.types.default');

		$this->set($this->passedArgs);
		$element = String::insert(
			Configure::read('Flour.Configuration.types.pattern'), 
			array('type' => $type)
		);
		$this->render('/elements/'.$element, 'ajax');
	}

}
