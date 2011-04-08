<?php
/**
 * ContentsController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 **/
class ContentsController extends FlourAppController
{

/**
 * which models to load
 *
 * @var string $uses
 * @access public
 */
	public $uses = array('Flour.Content');

/**
 * controls pagination
 *
 * @var array $paginate
 * @access public
 */
	public $paginate = array(
		'Content' => array(
			'limit' => 100,
		)
	);

/**
 * lists all available contents, accepts a variety of named params
 *
 * {{{
 *	/contents/index/view:short #show all items with template 'short' (from item.ctp)
 *	/contents/index/search:foo #find all items with 'foo'
 *	/contents/index/tags:bar #find all items tagged 'bar'
 *	/contents/index/type:foo #find all items of type 'foo' (aka filter results)
 * }}}
 *
 * @return void
 * @access public
 */
	public function admin_index()
	{
		$conditions = $this->Search->buildConditions('Content');
		$this->data = $this->paginate('Content', $conditions);
		$this->set('tags', 
			$this->Content->Tagged->find('cloud', array(
				'model' => 'Content',
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
				__('Invalid Content.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Content->get($id);
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
			$model = $this->data['Content']['model'];
			$modelArray = pluginSplit($model);
			$modelName = $modelArray[1];
			$this->$modelName = ClassRegistry::init($model);
			$this->$modelName->create($this->data);
			$this->Content->create($this->data);
			$valid1 = $this->$modelName->validates();
			$valid2 = $this->Content->validates();
			
			if($valid1 && $valid2)
			{
				$save1 = $this->$modelName->save();
				if($save1)
				{
					$model_id = $this->$modelName->getInsertID();
					$this->data['Content']['foreign_id'] = $model_id;
					$this->Content->create($this->data);
					$save2 = $this->Content->save();
					if($save2)
					{
						$this->data['Content']['id'] = $this->Content->getInsertID();
						$this->Activity = ClassRegistry::init('Flour.Activity');
						$this->Activity->write('content_created', $this->data, 'Content');
						$this->Flash->success(
							__('Content :Content.name saved.', true),
							array('action' => 'edit', $this->data['Content']['id'])
						);
					} else {
						$this->Flash->error(
							__('Content :Content.name could not be saved.', true)
						);
					}
				} else {
					$this->Flash->error(
						__(':Content.model could not be saved.', true)
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
				__('Invalid Content.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		if(!empty($this->data))
		{
			$model = $this->data['Content']['model'];
			$modelArray = pluginSplit($model);
			$modelName = $modelArray[1];
			$this->$modelName = ClassRegistry::init($model);
			$this->data[$modelName]['id'] = $this->data['Content']['foreign_id'];
			$this->$modelName->create($this->data);
			$this->Content->create($this->data);
			$valid1 = $this->$modelName->validates();
			$valid2 = $this->Content->validates();
			
			if($valid1 && $valid2)
			{
				$save1 = $this->$modelName->save();
				$save2 = $this->Content->save();
				if($save1 && $save2)
				{
					$this->Activity = ClassRegistry::init('Flour.Activity');
					$this->Activity->write('content_updated', $this->data, 'Content');
					$this->Flash->success(
						__('Content :Content.name saved.', true),
						array('action' => 'edit', $this->data['Content']['id'])
					);
				} else {
					$this->Flash->error(
						__('Content :Content.name could not be saved.', true)
					);
				}
			}
		} else {
			$this->data = $this->Content->get($id);
		}
		$this->set('type', $this->data['Content']['type']);
		$this->set('editions', $this->Content->find('editions'));
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
				__('Invalid Content.', true),
				$this->referer(array('action' => 'index'))
			);
		}
		$this->data = $this->Content->read(null, $id);
		$result = $this->Content->delete($id);
		if(!$result)
		{
			return $this->Flash->error(
				__('Content :Content.name could not be deleted.', true),
				$this->referer(array('action'=>'index'))
			);
		}
		$this->Activity = ClassRegistry::init('Flour.Activity');
		$this->Activity->write('content_object_deleted', $this->data, 'Content');
		$this->Flash->success(
			__('Content :Content.name successfully deleted.', true),
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
			Configure::read('Flour.Content.types.pattern'), 
			array('type' => $type)
		);
		$this->render('/elements/'.$element, 'ajax');
	}

}
