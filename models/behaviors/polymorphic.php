<?php
/**
 * Model behavior Polymorphic.
 *
 * This behavior works with plugins, also.
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 */
class PolymorphicBehavior extends ModelBehavior {
	
	private $__polyConditions = null;

	private $__defaultSettings = array(
		'classField' => 'model',
		'foreignKey' => 'foreign_id'
	);

	public function setup(&$Model, $settings = array())
	{
		$this->settings[$Model->alias] = array_merge(
			$this->__defaultSettings,
			$settings
		);
	}

	public function beforeFind(&$Model, $queryData) {
        // You can set conditions for each model associated with the polymorphic model.
		if (isset($queryData['polyConditions'])) {
			$this->__polyConditions = $queryData['polyConditions'];
			unset($queryData['polyConditions']);
		}
		return $queryData;
	}

	public function afterFind (&$Model, $results, $primary = false) {
		extract($this->settings[$Model->alias]);
		if ($primary && isset($results[0][$Model->alias][$classField])) {
			foreach ($results as $key => $result) {
				$associated = array();
				$class = $result[$Model->alias][$classField];
				$classArray = pluginSplit($class);
				$className = $classArray[1];
				$foreignId = $result[$Model->alias][$foreignKey];
				if ($className && $foreignId) {
					$associatedConditions = array(
						'conditions' => array(
							$className . '.id' => $foreignId
						)
					);
					if (isset($this->__polyConditions[$class])) {
						$associatedConditions = Set::merge($associatedConditions, $this->__polyConditions[$class]);
					}
					$result = $result[$Model->alias];
					if (!isset($Model->$className)) {
						$Model->bindModel(array('belongsTo' => array(
							$className => array(
								'className' => $class,
								'conditions' => array($Model->alias . '.' . $classField => $class),
								'foreignKey' => $foreignKey
							)
						)));
					}

					$associated = $Model->$className->find('first', $associatedConditions);
					$associated[$className]['display_field'] = $associated[$className][$Model->$className->displayField];
                    $results[$key][$className] = $associated[$className];
                    unset($associated[$className]);
                    $results[$key][$className] = Set::merge($results[$key][$className], $associated);
				}
			}
		} elseif(isset($results[$Model->alias][$classField])) {
			$associated = array();
			$class = $results[$Model->alias][$classField];
			$classArray = pluginSplit($class);
			$className = $classArray[1];
			$foreignId = $results[$Model->alias][$foreignKey];
			if ($className && $foreignId) {
				$result = $results[$Model->alias];
				if (!isset($Model->$class)) {
					$Model->bindModel(array('belongsTo' => array(
						$className => array(
							'className' => $class,
							'conditions' => array($Model->alias . '.' . $classField => $class),
							'foreignKey' => $foreignKey
						)
					)));
				}
				$associated = $Model->$className->find(array($className.'.id' => $foreignId), array('id', $Model->$className->displayField), null, -1);
				$associated[$className]['display_field'] = $associated[$className][$Model->$class->displayField];
				$results[$className] = $associated[$className];
			}
		}
		return $results;
	}
}
?>