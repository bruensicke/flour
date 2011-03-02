<?php
/**
 * TplHelper
 *
 * Capable of constructing and parsing different template-tags in a lightweight manner.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 */
App::import('Lib', 'Flour.init');
class TplHelper extends AppHelper
{

/**
 * Character used for bracketing the elements
 * Default is { and }, could be changed to [ and ] if you prefer that...
 *
 * @var array $tag_chars
 * @access public
 */
	public $tag_chars = array('OPENTAG' => '{', 'CLOSETAG' => '}');

/**
 * These are the Regexed used by the formatter.
 * OPENTAG and CLOSETAG will be replaced accordingly. supports everything from {}, [], [[]] and %%, %%%% and even <start> and <stop> ...
 *
 * @var array $tag_regex
 * @access public
 */
	public $tag_regex = array(
			'default' => '/OPENTAG(?:"[^"]*"|\'[^\']*\'|(?:(?!OPENTAG|CLOSETAG)[^"\'])+)*CLOSETAG/U', //A: finds all {elements}
			'extract_func' => '/^OPENTAG(\S+)(.*)CLOSETAG$/s', //B: seperates func-name and params
			'extract_params' => '/\s+([^\s=]+)(?:\s*=\s*((?:"[^"]*"|\'[^\']*\'|[^\s"\'])+))?/', //C: finds all individual key=value pairs.
			'dimensions' => '/([0-9]+(x)+[0-9]+)/msi', //finds all 180x280
		);

/**
 * Here are special tags that do not need to be full qualified.
 * key is name of tag, value is full qualified call
 * 
 * The call has the following structure:
 * 1. html.link = <helper>.<method>
 * 2. flour.markdown.transform = <plugin>.<helper>.<method>
 *
 * @var array $tag_specials
 * @access public
 */
	public $tag_specials = array(
		'site' => 'core.s14.site', 
		'public' => 'public',
		'app' => 'cms.cms.parse_app', 
		'element' => 'cms.cms.parse_element'
	);

/**
 * if set to true, all placeholders will be visible. Set to false, to hide them.
 * 
 * @var bool $showPlaceholders
 * @access public
 */
	public $showPlaceholders = true;

/**
 * If requested, will be instance of Markdown parser
 * 
 * @var object Markdown
 * @access public
 */
	public $_markdown = null;

/**
 * If requested, will be instance of Mustache parser
 * 
 * @var object Mustache
 * @access public
 */
	public $_mustache = null;

/**
 * Template Model, will be set on _init()
 * 
 * @var Model $_Template
 * @access protected
 */
	protected $_Template = false;

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

	//gets content from given element, first checks APP, then FLOUR, in both VIEWS/elements/templates
	public function element($name, $data = array())
	{
		if(file_exists(ELEMENTS.'templates/'.$name.'.ctp'))
		{
			return $this->_View->element('templates/'.$name, $data);
		}
		if(file_exists(FLOUR.'/views/elements/templates/'.$name.'.ctp'))
		{
			$data['plugin'] = 'flour';
			return $this->_View->element('templates/'.$name, $data);
		}
		return $this->_View->element('templates/'.$name, $data);
	}

	//checks, if given element exists
	public function elementExists($name)
	{
		if(file_exists(ELEMENTS.'templates/'.$name.'.ctp'))
		{
			return true;
		}
		if(file_exists(FLOUR.'/views/elements/templates/'.$name.'.ctp'))
		{
			return true;
		}
		return false;
	}

	public function render($template, $data = array(), $options = array())
	{
		$options = array_merge(array('engine' => 'insert'), $options);

		if($this->elementExists($template)) $template = $this->element($template);

		switch(low($options['engine']))
		{

			case 'mustache':
			case 'mustache::insert':
				$output = $this->mustache($template, $data, $options);
				if(low($options['engine']) == 'mustache')
				{
					break;
				} else {
					$template = $output;
				}


			case 'markdown':
			case 'markdown::insert':
				$output = $this->markdown($template);
				if(low($options['engine']) == 'markdown')
				{
					break;
				} else {
					$template = $output;
				}

			case 'string::insert':
			case 'insert':
			default:
				$output = String::insert($template, $data);
		}
		return $output;
	}

	public function type($type, $data = array(), $options = array())
	{
		$element_name = $this->element($type);
		return $this->render($element_name, $data, $options);
	}

/**
 * renders given $data with Template given via $slug
 *
 * @param string $slug slug of Template to render
 * @param array $data array with data to be inserted into the template
 * @param array $options array with options to control template rendering
 * @return string $output the HTML rendered by the widget
 * @access public
 */
	public function slug($slug, $data = array(), $options = array())
	{
		$row_data = $this->get($slug);
		if(empty($row_data['Template']) || !is_array($row_data['Template']))
		{
			//nothing in db, check filesystem
			// $row_data['Template']['content'] = file_get_contents('')
			return false;
		}
		$data = array_merge($data, $row_data['Template']);
		return $this->render($row_data['Template']['content'], $data, $options);
	}

/**
 * gets the current Template with given $slug
 *
 * @param string $slug slug of Template to retrieve (or id)
 * @return mixed array of $data, if found in database for the currently active content, false otherwise
 * @access public
 */
	public function get($slug)
	{
		return $this->_Template->get($slug);
	}

/**
 * converts markdown to html
 *
 * @param  string $text Text in markdown format
 * @return string parsed $text
 */
	public function markdown($content = null)
	{
		if($this->elementExists($template)) $template = $this->element($template);
		if ($this->_markdown === null)
		{
			App::import('Vendor', 'Flour.MarkdownParser');
			$this->_markdown = new Markdown_Parser;
		}
		return $this->_markdown->transform($content);
	}

/**
 * converts markdown to html
 *
 * @param  string $text Text in markdown format
 * @return string parsed $text
 */
	public function mustache($template, $data = array(), $options = array())
	{
		if($this->elementExists($template)) $template = $this->element($template);
		if ($this->_mustache === null)
		{
			App::import('Vendor', 'Flour.Mustache');
			$this->_mustache = new Mustache;
		}
		return $this->_mustache->render($template, $data, $options);
	}

/**
 * Will replace all Tags in $data or all elements of $data if $data is an array
 *
 * @param mixed $data Array or String to search and replace all given tags within
 * @return string $str Formatted string with all tags replaced
 * @access public
 */
	public function format($data)
	{
		if(is_array($data))
		{
			foreach($data as $datakey => $datafield)
			{
				if(is_array($datafield))
				{
					$datafield = $this->format($datafield); //TODO: test, if recursion works
				} else {
					$data[$datakey] = $this->parse($datafield); //parsed datafield back to data-array
				}
			}
		} else {
			$data = $this->parse($data);
		}
		return $data;
	}

/**
 * Applies all found filters to $str and will do the magic
 *
 * @param mixed $data Array or String to search and replace all given tags within
 * @param array $data Array with $key => $value pairs, that will be replaced right away (useful for loops)
 * @return string $str Formatted string with all tags replaced
 */
	public function parse($string, $data = array() )
	{
		preg_match_all($this->_tag('default'), $string, $matchs);
		if( count($matchs[0]) > 0 )
		{
			foreach ($matchs[0] as $value)
			{
				if(array_key_exists($value, $data)) //processing $data Array here right now, makes nesting possible ;)
				{
					$replacer = $data[$value];
				} else { //there is no matching key in $data, examine further
					$replacer = $this->convert($value);
				}
				$string = str_replace($value, $replacer, $string);
			}
		}
		return $string;
	}

	/**
	 *
	 * Replaces [element:element_name] tags in a string with
	 * output from cakephp elements
	 * @param string $value a string with everything inbetween OPENTAG and CLOSETAG. It will be examined as follows:
	 *
	 * plugin.helper.method
	 * [plugin.]helper.method
	 * helper.method
	 *
	 * plugin.helper.method params
	 * plugin.helper.method param1=val1 param2='val2' param3="val3"
	 *
	 * @return string the string that will be replaced for $value
	 */
	function convert($value)
	{
		preg_match($this->_tag('extract_func'), $value, $call);
		list($trash, $func, $param_list) = $call; //$trash is identical to $value, $func is everything before first ' ' and $paramlist is everything beyond that
		preg_match_all($this->_tag('extract_params'), $param_list, $params);
		if(count($params[1])) //only if params exist, we convert them to an array
		{
			$parms = array_combine($params[1], $params[2]);
		} else {
			$parms = array();
		}

		$dispatch_function = split('\.', $func); //we have to see what is in that call
		//debug($dispatch_function);

		switch(substr_count($func, '.')) //see, if func-name is complete.
		{
			case 1:
				
				//one missing, check array, if not, append same to do normal
				list($first, $second) = split('\.', $func);
				//debug($first);
				if(array_key_exists($first, $this->_tag_specials))
				{
					$first = $this->_tag_specials[$first];
					$func = $first.'.'.$second;
					//do the special thing

					//call $first with $second as param
					if(is_array($parms))
					{
						$parms[] = $second; 
					} else {
						$parms = empty($parms) ? $second : $second.' '.$parms;
					}
						
				} else {
					//prepend the correct prefix
					$func = $first.'.'.$func;
				}
			case 2:
			default:
				//normal
				//debug($func);
				list($plugin, $helper, $method) = split('\.', $func);
				//load helper, do method with $parms

				//caching
				//debug($parms);

				$current = $plugin.'.'.$helper;
				unset($this->_View->loaded['cms']);
				$loadedHelpers = array_keys($this->_View->loaded);
				if(!in_array($current, $loadedHelpers))
				{
					$this->_View->_loadHelpers(&$this->_View->loaded, array($current), 's14');
				}
		
		}
		$converted_string = $this->_View->loaded[$helper]->$method($parms);
		$converted_string = !empty($converted_string) ? $converted_string : '';
		
		//
		$value = $converted_string;
		return $value;
	}


/**
 * Returns the regex with the correct OPEN and CLOSE tags (i.e. "{" and "}" or "[" and "]").
 *
 * @param string $type Name of regular expression
 * @return string $regex Regex with correct replaced OPEN and CLOSE tags
 * @access protected
 */
	protected function _tag($type = 'default')
	{
		$regex = $this->_tag_regex[$type];
		foreach($this->_tag_chars as $key => $value)
		{
			$regex = str_replace($key, preg_quote($value), $regex);
		}
		return $regex;
	}

/**
 * will be executed on initialization, references the current
 * View Class, to be able to render elements.
 * 
 * if database is connected, loads Template Model, to interact with TemplateLibrary
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
		
		if(!$this->_Template)
		{
			$this->_Template = ClassRegistry::init('Flour.Template');
		}
		return true;
	}
}
