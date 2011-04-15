<?php
/**
 * Collection Model
 * 
 * a Collection can save unlimited amount of CollectionItems
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class Collection extends FlourAppModel
{

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'Collection';

	/**
	 * Attached behaviors
	 *
	 * @var array
	 * @access public
	 */
	public $actsAs = array(
		'Flour.Taggable',
	);

	/**
	 * hasMany associations
	 *
	 * @var array
	 * @access public
	 */
	public $hasMany = array(
		'CollectionItem' => array(
			'className' => 'Flour.CollectionItem',
			'foreignKey' => 'collection_id',
			'dependent' => true,
			'order' => 'CollectionItem.sequence ASC',
		)
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 * @access public
	 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

	/**
	 * beforeSave callback, will be fired on save
	 *
	 * @param array $options 
	 * @return bool returns true to continue with save-operation, false stops the save.
	 * @access public
	 */
	public function beforeSave($options = array())
	{
		if(!empty($this->id))
		{
			$conditions = array(
				'CollectionItem.collection_id' => $this->id,
			);
			$this->CollectionItem->deleteAll($conditions);
		}
		return true;
	}

}
