<?php
/**
 * Content Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Content extends FlourAppModel
{
	var $actsAs = array(
		'Flour.Polymorphic',
		'Flour.Editionable',
		'Flour.Taggable',
	);

	/**
	 * @var array controls validation
	 * @access private
	 */
	var $validate = array(
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
 * binds the model, associated via model and foreign_id.
 *
 * @param array $data 
 * @return bool
 * @access public
 */
	function _bind($data = array())
	{
		$this->data = (!empty($data))
			? $data
			: $this->data;

		if(isset($this->data['Content']['model']))
		{
			$model = (isset($this->data['Content']['model']))
				? $this->data['Content']['model']
				: 'Flour.ContentObject';

			$foreignModel = ClassRegistry::init($model);

			$this->bindModel(
				array('belongsTo' => array(
						$foreignModel->alias => array(
							'className' => $model,
							// 'conditions' => array('model' => $model),
							'foreignKey' => 'foreign_id',
						)
					)
				)
			);
			$this->{$foreignModel->alias}->bindModel(
				array('hasOne' => array(
					'Content' => array(
						'className' => 'Flour.Content',
						'foreignKey' => 'foreign_id',
						'dependant' => true,
					)
				))
			);
			$this->{$foreignModel->alias}->data = $this->data;
			return true;
		}
		return false;
	}
}
?>