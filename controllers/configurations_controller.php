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
		$this->data = $this->paginate('Configuration', $conditions);
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
			$model = $this->data['Configuration']['model'];
			$modelArray = pluginSplit($model);
			$modelName = $modelArray[1];
			$this->$modelName = ClassRegistry::init($model);
			$this->$modelName->create($this->data);
			$this->Configuration->create($this->data);
			$valid1 = $this->$modelName->validates();
			$valid2 = $this->Configuration->validates();
			
			if($valid1 && $valid2)
			{
				$save1 = $this->$modelName->save();
				if($save1)
				{
					$model_id = $this->$modelName->getInsertID();
					$this->data['Configuration']['foreign_id'] = $model_id;
					$this->Configuration->create($this->data);
					$save2 = $this->Configuration->save();
					if($save2)
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
					$this->Flash->error(
						__(':Configuration.model could not be saved.', true)
					);
				}
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
			$model = $this->data['Configuration']['model'];
			$modelArray = pluginSplit($model);
			$modelName = $modelArray[1];
			$this->$modelName = ClassRegistry::init($model);
			$this->data[$modelName]['id'] = $this->data['Configuration']['foreign_id'];
			$this->$modelName->create($this->data);
			$this->Configuration->create($this->data);
			$valid1 = $this->$modelName->validates();
			$valid2 = $this->Configuration->validates();
			
			if($valid1 && $valid2)
			{
				$save1 = $this->$modelName->save();
				$save2 = $this->Configuration->save();
				if($save1 && $save2)
				{
					$this->Flash->success(
						__('Configuration :Configuration.name saved.', true),
						array('action' => 'edit', $this->data['Configuration']['id'])
					);
				} else {
					$this->Flash->error(
						__('Configuration :Configuration.name could not be saved.', true)
					);
				}
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


}
