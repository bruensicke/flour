<?php
/**
 * DocsController
 * 
 * Shows Documentation of Flour.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class DocsController extends FlourAppController
{

	public $uses = null;
	
	public $helpers = array('Flour.Markdown');

	public function index()
	{
		$path = func_get_args();
		if(empty($path))
		{
			$path = array('index');
		}

		//TODO: match against existing plugins
		// $available_plugins = App::objects('plugin');
		$scope = (isset($this->passedArgs['scope']))
			? $this->passedArgs['scope']
			: 'flour';

		$root = ($scope == 'app')
			? APP.'/docs'
			: FLOUR.'/docs';

		$file = implode('/', $path);

		App::import('Folder');
		$Folder = new Folder();
		$folders = $Folder->tree($root, true, 'dir');
		function remove_path(&$item, $key, $prefix)
		{
			$item = trim(str_replace($prefix, '', $item), '/');
		}
		array_walk($folders, 'remove_path', $root);
		$folders = array_filter($folders);

		if(in_array($file, $folders))
		{
			$file .= '/index';
		}

		if(!file_exists("$root/$file.md"))
		{
			//TODO: throw wrong path error
			return;
		}
		$content = file_get_contents("$root/$file.md");
		$this->set(compact('file', 'folders', 'content'));
	}
	
/**
 * Index action.
 *
 * @access public
 */
	function admin_index()
	{

	}

/**
 * Install action.
 *
 * @access public
 */
	function admin_install()
	{

	}

/**
 * Contents action.
 *
 * @access public
 */
	function admin_contents()
	{

	}

/**
 * Widgets action.
 *
 * @access public
 */
	function admin_widgets()
	{

	}

}
?>