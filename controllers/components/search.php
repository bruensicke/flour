<?php
/**
 * Search Component
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class SearchComponent extends Object 
{
/**
 * All url-triggered search modes, this component supports
 *
 * @var array $_searchModes
 * @access protected
 */
	protected $_searchModes = array(
		'fullsearch',
		'type',
		'search',
		'tags',
		'date',
		'from',
		'to',
		'status',
	);

/**
 * if set to true, will preserve all named params in urls, on redirects
 *
 * @var boolean $preserveNamedParams
 * @access public
 */
	public $preserveNamedParams = true;

/**
 * calling controller object
 *
 * @var Controller $Controller
 * @access protected
 */
	protected $Controller = null;

/**
 * passedArgs, will be set in initialize
 *
 * @var array $passedArgs
 * @access protected
 */
	protected $passedArgs = null;

/**
 * Intialize Callback
 *
 * @param object Controller object
 * @access public
 */
	public function initialize(&$controller)
	{
		$this->Controller = $controller;
		$this->passedArgs = $controller->passedArgs;

		// if(!in_array('Session', $this->Controller->components))
		// {
		// 	array_unshift($this->Controller->components, 'Session');
		// }
	}

/**
 * startup Callback will be exectured before Controller action, but after beforeFilter
 *
 * @access public
 */
	public function startup()
	{
		$model = !empty($this->Controller->modelNames[0])
			? $this->Controller->modelNames[0]
			: null;

		if(!empty($model) && !empty($_POST) && isset($_POST['data'][$model]['search']))
		{
			$this->redirect($_POST['data'][$model]);
		}
	}

/**
 * generates an conditions array for searches and paginate, that consists of search-params coming in from named params.
 * Only named params that are present in $this->_searchModes are available
 * 
 * usage via Model->alias:
 * {{{
 *	$this->Search->buildConditions('Content');
 * }}}
 *
 * usage via search_fields:
 * {{{
 *	$this->Search->buildConditions(array(
 * 		'Project.name',
 * 		'Project.owner',
 * 		'ProjectDetails.description',
 * 		'ProjectDetails.status',
 * 		'ProjectMessage.body',
 * ));
 * }}}
 *
 * @param mixed $search_fields The alias of the model or an array with fields in the database to search in
 * @return array
 * @access public
 */
	public function buildConditions($search_fields = null)
	{
		//if first param is omitted, we find out which model to use
		if(is_null($search_fields) && !empty($this->Controller->modelNames[0]))
		{
			$search_fields = $this->Controller->modelNames[0];
		}

		//looks like first param is ModelName
		if(is_string($search_fields)
			&& isset($this->Controller->$search_fields)
			&& is_object($this->Controller->$search_fields)
		)
		{
			$schema = $this->Controller->$search_fields->schema();
			$alias = $this->Controller->$search_fields->alias;
			$search_fields = array();
			foreach($schema as $fieldname => $attributes)
			{
				$search_fields[] = $alias.'.'.$fieldname;
			}
		}

		$search_fields = (is_string($search_fields))
			? array($search_fields)
			: $search_fields;

		$alias = (empty($alias))
			? $this->Controller->modelNames[0]
			: $alias;

		$conditions = array();
		foreach($this->passedArgs as $key => $value)
		{
			if(in_array($key, $this->_searchModes))
			switch($key)
			{
				case 'fullsearch':
					$current_searchterms = split(' ', $value); //too bad... see: https://trac.cakephp.org/ticket/5449
					while (!empty($current_searchterms))
					{
						$term = array_shift($current_searchterms);
						$type = 'OR';
						$not = ($term{0} == '-');

						// force/ignore word by prepending +/-
						if (in_array($term{0}, array('+', '-')))
						{
							$type = 'AND';
							$term = ltrim($term, '+-');
						}

						// handle protected strings
						if ($term{0} == '"')
						{
							while (!empty($term_buffer) && substr($term, -1, 1) != '"')
							{
								$term .= " ".array_shift($term_buffer);
							}
							$term = trim($term, '"');
						}
						if ($term != '')
						{
							foreach ($search_fields as $field)
							{
								$not = ($not)
									? "NOT "
									: "";
								$conditions[$type][] = "{$field} {$not}LIKE '%{$term}%'";
							}
						}
					}
					$this->Controller->set('current_searchterms', explode(' ', $value));
					break;

				case 'search':
				
					// add + to every word, if it's not explicitly set to -
					// add * after every word to also in-/exclude part-words
					$current_searchterms = explode(' ', $value);
					foreach($current_searchterms as &$term) {
						$term = ltrim($term, '+');
						if(substr($term,0,1) != '-') {
							$term = '+'.$term;
						}
						if(substr($term,-1,1) != '*') {
							$term .= '*';
						}
					}
					$db_value = addcslashes(implode(' ', $current_searchterms), "\"'%");
					$sql_search_mode = 'NATURAL LANGUAGE';
					$sql_search_mode = 'BOOLEAN';
					$conditions['AND'][] = 'MATCH('.implode(', ', $search_fields).') AGAINST(\''.$db_value.'\' IN '.$sql_search_mode.' MODE )';
					$this->Controller->set('current_searchterms', $value);

					break;

				case 'tags':
					if(!stristr($value, ','))
					{
						$conditions[$alias.'.tags LIKE'] = '%'.$value.'%';
						$this->Controller->set('current_tags', $value);
					} else {
						$type = (true) //TODO: find good condition
							? 'OR'
							: 'AND';
						$current_tags = explode(',', $value);
						foreach($current_tags as $tag)
						{
							$conditions[$type][] = $alias.'.tags LIKE \'%'.$tag.'%\''; //'%'.$value.'%';
						}
						$this->Controller->set('current_tags', $current_tags);
					}
					break;

				case 'date':
					//split into from to (if available)

					if(stristr($value, ' - '))
					{
						list($from, $to) = split(' - ', $value, 2);
						list($f_d, $f_m, $f_y) = split('\.', $from, 3);
						list($t_d, $t_m, $t_y) = split('\.', $to, 3);
						$from = '20'.$f_y.'-'.$f_m.'-'.$f_d.' 00:00:00';  //concatenate mysql-conform date-string
						$to = '20'.$t_y.'-'.$t_m.'-'.$t_d.' 23:59:59'; //concatenate mysql-conform date-string
						$conditions[$alias.'.created >='] = $from;
						$conditions[$alias.'.created <='] = $to;
					} else {
						//TODO: refactor to mysql-date (Y-m-d)
						list($f_d, $f_m, $f_y) = split('\.', $value, 3);
						$value = '20'.$f_y.'-'.$f_m.'-'.$f_d; //concatenate mysql-conform date-string
						$conditions[$alias.'.created LIKE'] = $value.'%';
					}
					$searchterms = split(' - ', $value);
					$this->Controller->set('current_search_range', $searchterms);
					$this->Controller->set('current_searchtype', $key);
					break;

				case 'from':
					$conditions[$alias.'.created >='] = $value.'%';
					break;

				case 'to':
					$conditions[$alias.'.created <='] = $value.'%';
					break;

				case 'status':
					$value = split(',', $value); //too bad... see: https://trac.cakephp.org/ticket/5449
					$conditions[$alias.'.status'] = $value;
					break;

				case 'type':
					$value = split(',', $value); //too bad... see: https://trac.cakephp.org/ticket/5449
					$conditions[$alias.'.type'] = $value;
					break;

				//TBD.
				default:
					$conditions[$key] = Sanitize::paranoid($value, array('-', '.')); //every key (that is in $this->_searchModes) will be put directly as condition
			}
		}
		return $conditions;
	}

	function _addSearchModes($modes)
	{
		if(!is_array($modes)) $modes = array($modes);
		$this->_searchModes = array_merge($this->_searchModes, $modes);
	}

/**
 * generates a url with current search and redirects to index() with corresponding parameters.
 *
 * @param string $searchterm Term to search for (can be multiple terms, with + and -)
 * @return NULL
 * @access public
 */
	public function redirect($data = array())
	{
		if(isset($data['params']))
		{
			$params = json_decode($data['params'], true);
			unset($data['params']);
			$data = array_merge($params, $data);
		}
		$url = array(
			'controller' => $this->Controller->params['controller'],
			'action' => $this->Controller->params['action'],
		);
		$url = array_merge($url, $data);
		$this->Controller->redirect($url); //TODO: check array //we redirect here, so we have named params again
	}


}