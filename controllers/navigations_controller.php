<?php
/**
 * NavigationsController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class NavigationsController extends FlourAppController
{

/**
 * which models to load
 *
 * @var string $uses
 * @access public
 */
	public $uses = array('Flour.Navigation');

/**
 * controls pagination
 *
 * @var array $paginate
 * @access public
 */
	public $paginate = array(
		'Navigation' => array(
			'limit' => 100,
		)
	);

/**
 * lists all available navigations, accepts a variety of named params
 *
 * {{{
 *	/navigations/index/view:short #show all items with template 'short' (from item.ctp)
 *	/navigations/index/search:foo #find all items with 'foo'
 *	/navigations/index/tags:bar #find all items tagged 'bar'
 * }}}
 *
 * @return void
 * @access public
 */
	public function admin_index()
	{
		$conditions = $this->Search->buildConditions('Navigation');
		$this->data = $this->Navigation->find('all', compact('conditions'));
		$this->set('tags', 
			$this->Navigation->Tagged->find('cloud', array(
				'model' => 'Navigation',
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
				__('Invalid Navigation.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Navigation->read(null, $id);
	}

/**
 * add action
 * Handles several named params for itself, like
 * 
 * {{{
 *	/navigations/add/type:foo #content of type 'foo'
 *	/navigations/add/status:2 #set status to 2
 *	/navigations/add/name:foo #pre-fill name with foo
 *	/navigations/add/slug:bar #pre-fill slug with bar
 *	/navigations/add/tags:baz #pre-fill tags with baz
 *	/navigations/add/name:foo/slug:bar/type:baz #pre-fill all fields accordingly
 * }}}
 *
 * @return void
 * @access public
 */	
	public function admin_add()
	{
		if(!empty($this->data))
		{
			$this->Navigation->create();
			$valid = $this->Navigation->saveAll($this->data, array('validate' => 'only'));
			if(!$valid)
			{
				return;
			}
			$saved = $this->Navigation->saveAll($this->data, array('validate' => false));
			if($saved)
			{
				$id = $this->Navigation->getInsertID();
				$this->Flash->success(
					__('Navigation :Navigation.name saved.', true),
					array('action' => 'edit', $id)
				);
			} else {
				$this->Flash->error(
					__('Navigation :Navigation.name could not be saved.', true),
					$this->referer(array('action' => 'index'))
				);
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
				__('Invalid Navigation.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		if(!empty($this->data))
		{
			$valid = $this->Navigation->saveAll($this->data, array('validate' => 'only'));
			if(!$valid)
			{
				return;
			}
			$saved = $this->Navigation->saveAll($this->data, array('validate' => false));
			if($saved)
			{
				$this->Flash->success(
					__('Navigation :Navigation.name saved.', true),
					array('action' => 'edit', $this->Navigation->id)
				);
			} else {
				$this->Flash->error(
					__('Navigation :Navigation.name could not be saved.', true),
					$this->referer(array('action' => 'index'))
				);
			}
		} else {
			$this->data = $this->Navigation->read(null, $id);
		}
		$this->set('editions', $this->Navigation->find('editions'));
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
				__('Invalid Navigation.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Navigation->read(null, $id);
		$result = $this->Navigation->delete($id);
		if(!$result)
		{
			return $this->Flash->error(
				__('Navigation :Navigation.name could not be deleted.', true),
				$this->referer(array('action'=>'index'))
			);
		}
		$this->Flash->success(
			__('Navigation :Navigation.name successfully deleted.', true),
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
			Configure::read('Flour.Navigation.types.pattern'), 
			array('type' => $type)
		);
		$this->render('/elements/'.$element);
	}

}
