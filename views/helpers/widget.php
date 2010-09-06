<?php
/**
 * WidgetHelper
 *
 * Capable of constructing and displaying complex widget-based layouts.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config/flour.php');
class WidgetHelper extends AppHelper
{

/**
 * array of rows, that are already parsed
 * 
 * @var array $_output
 * @access protected
 */
	protected $_output = array();

/**
 * will be used as seperator in rows-implosion
 * 
 * @var string $_seperator
 * @access protected
 */
	protected $_seperator = '';

/**
 * Widget Model, will be set on _init()
 * 
 * @var Model $_Widget
 * @access protected
 */
	protected $_Widget = false;

/**
 * Reference to View Class
 * needed, to render elements within a helper
 * 
 * @var ViewClass $_View
 * @access protected
 */
	protected $_View = false;
	
/**
 * Constructor method
 *
 * @access public
 */
	public function __construct()
	{
		$this->_init();
	}

/**
 * gets the current Widget with given $slug
 *
 * @param string $slug slug of Widget to retrieve
 * @return mixed array of $data, if found in database for the currently active content, false otherwise
 * @access public
 */
	public function get($slug_or_id)
	{
		$field = (Validation::uuid($slug_or_id))
			? 'id'
			: 'slug';

		$data = $this->_Widget->find('current', array($field => $slug_or_id));
		if(empty($data))
		{
			return false;
		}
		return $data;
	}

/**
 * renders the current Widget with given $slug
 *
 * @param string $slug slug of Widget to render
 * @param string $template name of template 
 * @param array $data array with data to be passed into the element
 * @return string $output the HTML rendered by the widget
 * @access public
 */
	public function render($slug, $data = array(), $options = array())
	{
		$row_data = $this->get($slug);
		if(empty($row_data['Widget']) || !is_array($row_data['Widget']))
		{
			return false;
		}
		$data = array_merge($options, $row_data['Widget']);
		return $this->_View->element('widget', $data);
	}

/**
 * renders a Widget of a given $type and given $data
 * 
 * {{{
 * echo $this->Widget->type('foo', array('bar' => 'baz'));
 * echo $this->Widget->type('Flour.html', array('content' => 'hello world.'));
 * }}}
 *
 * @param string $type which type to use
 * @param array $data put in all data, needed by this type of widget
 * @param array $options 
 * @return string $output the HTML rendered by the widget
 * @access public
 */
	public function type($type, $data = array(), $options = array())
	{
		$typeArray = pluginSplit($type);
		$type = $typeArray[1];
		$plugin = $typeArray[0];
		$data = array_merge($options, array(
			'type' => $type,
			'data' => $data,
		));
		$data['plugin'] = isset($plugin)
			? $plugin
			: 'Flour';
		return $this->_View->element('widget', $data);
	}

/**
 * renders a row of Widgets with given $template. See examples of how to use this.
 * {{{
 * $items = array();
 * //various widgets in one row, with different targets
 * $items[] = array('type' => 'foo');
 * $items[] = array('type' => 'foo', 'target' => 'b'); //specify a target (defaults to a)
 * $items[] = array('type' => 'foo', 'data' => array('bar' => 'baz')); //hand over data, to be used by widget
 * $items[] = array('type' => 'html', 'data' => array('content' => 'hello world.')); //working example
 *
 * $items[] = array('slug' => 'foo'); //use an instance of an already created widget, found by slug
 * $items[] = array('slug' => 'foo', 'target' => 'c'); //target this instance
 * $items[] = array('slug' => 'foo', 'data' => array('bar' => 'baz')); //overwrite data
 * 
 * echo $this->Widget->row($items, 'half'); //renders all of the above in template $half
 * }}}
 *
 * @param array $items array with at least 'type' or 'slug' set, see examples
 * @param string $template template to be used by this row
 * @param array $options for template
 * @return string $output the HTML rendered by the template, including all widgets
 * @access public
 */
	public function row($items, $template = 'Flour.full', $options = array())
	{
		$out = array();
		foreach($items as $index => $item)
		{
			$target = (isset($item['target']))
				? $item['target']
				: 'a';

			if(!isset($out[$target])) $out[$target] = '';

			switch (true)
			{
				case isset($item['type']):
					$out[$target] .= $this->type($item['type'], $item['data']);
					break;
				case isset($item['slug']):
					$out[$target] .= $this->render($item['slug']);
					break;
			}
		}

		//find correct plugin for template
		$templateArray = pluginSplit($template);
		$template = $templateArray[1];
		$options['plugin'] = (!isset($options['plugin']) && !empty($templateArray[0]))
			? $templateArray[0]
			: 'Flour';

		$out = $this->template($template, $out, $options);
		return $this->_addrow($out);
	}

/**
 * renders a given template.
 *
 * @param string $template the template to be used
 * @param array $items array with text to be inserted into template, keyed off with 'a', 'b', etc.
 * @param array $options available to template
 * @return string $output the HTML rendered by the template
 * @access public
 */
	public function template($template = 'full', $items = array(), $options = array())
	{
		return $this->_View->element(
			String::insert(Configure::read('Flour.Widget.templates.pattern'), array('template' => $template)),
			array_merge($options, $items)
		);
	}

/**
 * returns all rows, added by $this->row();
 * use this one, if you want to render more than one row at once.
 *
 * @param bool $reset set to true to reset rows after returning, defaults to false
 * @return string $output the HTML rendered by all templates in all rows
 * @access public
 */
	public function rows($reset = false)
	{
		$out = implode($this->_seperator, $this->_output);
		if($reset) $this->reset();
		return $out;
	}

/**
 * resets all rows, therefore allowing for a new row collection.
 *
 * @return void
 * @access public
 */
	protected function reset()
	{
		$this->_output = array();
	}

/**
 * returns full name of element of widget for a given $type. 
 *
 * @return string full name of element, to be used in $this->element();
 * @access public
 */
	public function element($type)
	{
		return String::insert(Configure::read('Flour.Widget.types.pattern'), array('type' => $type));
	}

/**
 * adds $html to output array, used by $this->rows();
 *
 * @param string $html
 * @return string $html the $html you just pasted in.
 * @access protected
 */
	protected function _addrow($html = '')
	{
		return $this->_output[] = $html;
	}

/**
 * will be executed on initialization, references the current
 * View Class, to be able to render elements.
 * 
 * if database is connected, loads Content Model, to interact with 
 * ContentLibrary
 *
 * @return bool returns true if running with connected db, false otherwise.
 * @access protected
 */
	protected function _init()
	{
		$this->_View = ClassRegistry::getObject('view');

		//first, check if we run with database
		if(!file_exists(CONFIGS.'database.php'))
		{
			//TODO: check for active connection.
			return false;
		}

		uses('model' . DS . 'connection_manager');
		$db = ConnectionManager::getInstance();
		$connected = $db->getDataSource('default');
		if (!$connected->isConnected())
		{
			return false;
		}
		
		if(!$this->_Widget)
		{
			$this->_Widget = ClassRegistry::init('Flour.Widget');
		}
		return true;
	}

}