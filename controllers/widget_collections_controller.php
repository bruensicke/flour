<?php
/**
 * WidgetCollectionsController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class WidgetCollectionsController extends FlourAppController
{

/**
 * controls pagination
 *
 * @var array $paginate
 * @access public
 */
	public $paginate = array(
		'WidgetCollection' => array(
			'limit' => 100,
		)
	);

/**
 * View action
 *
 * @param integer $id ID of record
 * @return void
 * @access public
 */	
	public function view($id_or_slug)
	{
		$layout = (isset($this->passedArgs['layout']))
			? $this->passedArgs['layout']
			: 'ajax';

		$this->render($this->action, $layout);
	}

/**
 * lists all available widgets, accepts a variety of named params
 *
 * {{{
 *	/widgets/index/view:short #show all items with template 'short' (from item.ctp)
 *	/widgets/index/search:foo #find all items with 'foo'
 *	/widgets/index/tags:bar #find all items tagged 'bar'
 * }}}
 *
 * @return void
 * @access public
 */
	public function admin_index()
	{
		$conditions = $this->Search->buildConditions('WidgetCollection');
		$this->data = $this->paginate('WidgetCollection', $conditions);
		$this->set('tags', 
			$this->WidgetCollection->Tagged->find('cloud', array(
				'model' => 'WidgetCollection',
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
				__('Invalid WidgetCollection.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->WidgetCollection->read(null, $id);
	}

/**
 * add action
 * Handles several named params for itself, like
 * 
 * {{{
 *	/widgets/add/type:foo #content of type 'foo'
 *	/widgets/add/status:2 #set status to 2
 *	/widgets/add/name:foo #pre-fill name with foo
 *	/widgets/add/slug:bar #pre-fill slug with bar
 *	/widgets/add/tags:baz #pre-fill tags with baz
 *	/widgets/add/name:foo/slug:bar/type:baz #pre-fill all fields accordingly
 * }}}
 *
 * @return void
 * @access public
 */	
	public function admin_add()
	{
		if(!empty($this->data))
		{
			$this->WidgetCollection->create();
			$valid = $this->WidgetCollection->saveAll($this->data, array('validate' => 'only'));
			if(!$valid)
			{
				return;
			}
			$saved = $this->WidgetCollection->saveAll($this->data, array('validate' => false));
			if($saved)
			{
				$id = $this->WidgetCollection->getInsertID();
				$this->Flash->success(
					__('WidgetCollection :WidgetCollection.name saved.', true),
					array('action' => 'edit', $id)
				);
			} else {
				$this->Flash->error(
					__('WidgetCollection :WidgetCollection.name could not be saved.', true),
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
				__('Invalid WidgetCollection.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		if(!empty($this->data))
		{
			$valid = $this->WidgetCollection->saveAll($this->data, array('validate' => 'only'));
			if(!$valid)
			{
				return;
			}
			$saved = $this->WidgetCollection->saveAll($this->data, array('validate' => false));
			if($saved)
			{
				$this->Flash->success(
					__('WidgetCollection :WidgetCollection.name saved.', true),
					array('action' => 'edit', $this->WidgetCollection->id)
				);
			} else {
				$this->Flash->error(
					__('WidgetCollection :WidgetCollection.name could not be saved.', true),
					$this->referer(array('action' => 'index'))
				);
			}
		} else {
			$this->data = $this->WidgetCollection->read(null, $id);
		}
		$this->set('type', $this->data['WidgetCollection']['type']);
		$this->set('editions', $this->WidgetCollection->find('editions'));
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
				__('Invalid WidgetCollection.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->WidgetCollection->read(null, $id);
		$result = $this->WidgetCollection->delete($id);
		if(!$result)
		{
			return $this->Flash->error(
				__('WidgetCollection :WidgetCollection.name could not be deleted.', true),
				$this->referer(array('action'=>'index'))
			);
		}
		$this->Flash->success(
			__('WidgetCollection :WidgetCollection.name successfully deleted.', true),
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
			: Configure::read('Flour.WidgetCollection.types.default');

		//we need a template to render
		$template = isset($this->passedArgs['template'])
			? $this->passedArgs['template']
			: 'admin';

		$this->set(compact('type', 'template'));
		$this->render($this->action, 'ajax');
	}

}
