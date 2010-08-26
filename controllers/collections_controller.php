<?php
/**
 * CollectionsController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class CollectionsController extends FlourAppController
{

/**
 * controls pagination
 *
 * @var array $paginate
 * @access public
 */
	public $paginate = array(
		'Collection' => array(
			'limit' => 100,
		)
	);

/**
 * lists all available collections, accepts a variety of named params
 *
 * {{{
 *	/collections/index/view:short #show all items with template 'short' (from item.ctp)
 *	/collections/index/search:foo #find all items with 'foo'
 *	/collections/index/tags:bar #find all items tagged 'bar'
 * }}}
 *
 * @return void
 * @access public
 */
	public function admin_index()
	{
		$conditions = $this->Search->buildConditions('Collection');
		$this->data = $this->Collection->find('all', compact('conditions'));
		$this->set('tags', 
			$this->Collection->Tagged->find('cloud', array(
				'model' => 'Collection',
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
				__('Invalid Collection.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Collection->read(null, $id);
	}

/**
 * add action
 * Handles several named params for itself, like
 * 
 * {{{
 *	/collections/add/type:foo #content of type 'foo'
 *	/collections/add/status:2 #set status to 2
 *	/collections/add/name:foo #pre-fill name with foo
 *	/collections/add/slug:bar #pre-fill slug with bar
 *	/collections/add/tags:baz #pre-fill tags with baz
 *	/collections/add/name:foo/slug:bar/type:baz #pre-fill all fields accordingly
 * }}}
 *
 * @return void
 * @access public
 */	
	public function admin_add()
	{
		if(!empty($this->data))
		{
			$this->Collection->create($this->data);
			if($this->Collection->validates())
			{
				if($this->Collection->save(null, false))
				{
					$id = $this->Collection->getInsertID();
					$this->Flash->success(
						__('Collection :Collection.name saved.', true),
						array('action' => 'edit', $id)
					);
				} else {
					$this->Flash->error(
						__('Collection :Collection.name could not be saved.', true)
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
				__('Invalid Collection.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		if(!empty($this->data))
		{
			$this->Collection->create($this->data);
			if($this->Collection->validates())
			{
				if($this->Collection->save(null, false))
				{
					$this->Flash->success(
						__('Collection :Collection.name saved.', true),
						array('action' => 'edit', $id)
					);
				} else {
					$this->Flash->error(
						__('Collection :Collection.name could not be saved.', true)
					);
				}
			} else {
				//validation errors
			}
		}
		$this->data = $this->Collection->read(null, $id);
		$this->set('type', $this->data['Collection']['type']);
		$this->set('editions', $this->Collection->find('editions'));
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
				__('Invalid Collection.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Collection->read(null, $id);
		$result = $this->Collection->delete($id);
		if(!$result)
		{
			return $this->Flash->error(
				__('Collection :Collection.name could not be deleted.', true),
				$this->referer(array('action'=>'index'))
			);
		}
		$this->Flash->success(
			__('Collection :Collection.name successfully deleted.', true),
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
		$type = $this->passedArgs['type'];
		$this->autoLayout = false;
		$this->autoRender = false;
		Configure::write('debug', 0);
		$element = String::insert(
			Configure::read('Flour.Collection.types.pattern'), 
			array('type' => $type)
		);
		$this->render('/elements/'.$element);
	}

}
