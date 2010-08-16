<?php
/**
 * Search Component
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class SearchComponent extends Object 
{
/**
 * if set to true, will preserve all named params in urls, on redirects
 *
 * @var public $preserveNamedParams
 * @access public
 */
	public $preserveNamedParams = true;

/**
 * Pointer to Controller Object
 *
 * @var protected $Controller
 * @access protected
 */
	protected $Controller = null;

/**
 * Intialize Callback
 *
 * @param object Controller object
 * @access public
 */
	public function initialize(&$controller)
	{
		$this->Controller = $controller;
		// if(!in_array('Session', $this->Controller->components))
		// {
		// 	array_unshift($this->Controller->components, 'Session');
		// }
	}
















/**
 * generates an conditions array for searches and paginate, that consists of search-params coming in from named params.
 * Only named params that are present in $this->_search_modes are available
 *
 * @param string $modelname The name of the Model to look for
 * @param mixed $search_fields The fields in the database to search in
 * @return array
 * @access private
 */
	function _buildConditions($modelname, $search_fields = array())
	{
		$conditions = array();
		//debug($this->data);
		foreach($this->params['named'] as $key => $value)
		{
			if(in_array($key, $this->_search_modes))
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
							foreach ($this->search_fields as $field)
							{
								$not = ($not) ? "NOT " : "";
								$conditions[$type][] = "{$field} {$not}LIKE '%{$term}%'";
							}
						}
					}
					$this->set('current_searchterms', split(' ', $value));
					break;

				case 'search':
				
					// add + to every word, if it's not explicitly set to -
					// add * after every word to also in-/exclude part-words
					$current_searchterms = split(' ', $value);
					foreach($current_searchterms as &$term) {
						$term = ltrim($term, '+');
						if(substr($term,0,1) != '-') {
							$term = '+'.$term;
						}
						if(substr($term,-1,1) != '*') {
							$term .= '*';
						}
					}
					$value = addcslashes(join(' ', $current_searchterms), "\"'%");
					
					$conditions['AND'][] = 'MATCH('.join(', ', $search_fields).') AGAINST(\''.$value.'\' IN BOOLEAN MODE )';

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
						$conditions[$modelname.'.created >='] = $from;
						$conditions[$modelname.'.created <='] = $to;
					} else {
						list($f_d, $f_m, $f_y) = split('\.', $value, 3);
						$value = '20'.$f_y.'-'.$f_m.'-'.$f_d; //concatenate mysql-conform date-string
						$conditions[$modelname.'.created LIKE'] = $value.'%';
					}
					$searchterms = split(' - ', $value);
					$this->set('current_search_range', $searchterms);
					$this->set('current_searchtype', $key);
					break;

				case 'from':
					$conditions[$modelname.'.created >='] = $value.'%'; //TODO: works?
					break;

				case 'to':
					$conditions[$modelname.'.created <='] = $value.'%'; //TODO: works?
					break;

				case 'status':
					$value = split(',', $value); //too bad... see: https://trac.cakephp.org/ticket/5449
					$conditions[$modelname.'.status'] = $value;
					break;

				default:
					$conditions[$key] = Sanitize::paranoid($value, array('-', '.')); //every key (that is in $this->_search_modes) will be put directly as condition
			}
		}
		return $conditions;
	}

	function _addSearchModes($modes)
	{
		if(!is_array($modes)) $modes = array($modes);
		$this->_search_modes = array_merge($this->_search_modes, $modes);
	}

/**
 * generates a url with current search and redirects to index() with corresponding parameters.
 *
 * @param string $searchterm Term to search for (can be multiple terms, with + and -)
 * @return NULL
 * @access public
 */
	function search($searchterm = null)
	{
		if(!empty($this->data))
		{
			$model = $this->data['Model']['name'];
			$action = (isset($this->data['Action']['name']))
				? $this->data['Action']['name']
				: 'index';
			$url = array_merge($this->data[$model], array('action' => $action, 'controller' => low(Inflector::pluralize($model))));
			$this->redirect($url); //TODO: check array //we redirect here, so we have named params again
		}
	}

/**
 * generates a url with current search and redirects to index() with corresponding parameters.
 *
 * @param string $searchterm Term to search for (can be multiple terms, with + and -)
 * @return NULL
 * @access public
 */
	function admin_search($searchterm = null)
	{
		if(!empty($this->data))
		{
			$model = $this->data['Model']['name'];
			$url = array_merge($this->data[$model], array('action' => 'index', 'controller' => low(Inflector::pluralize($model))));
			$this->redirect($url); //TODO: check array //we redirect here, so we have named params again
		}
	}

}